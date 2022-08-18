<?php

namespace app\store\repositories;

use app\lib\exception\ParameterException;
use thans\jwt\facade\JWTAuth;

/**
 * \app\store\repositories\StoreRepositories
 */
class StoreRepositories extends AbstractRepositories
{

    /**
     * store2Launch
     * @param array $userProfile
     * @return \think\response\Json
     */
    public function store2Launch(array $userProfile)
    {
        $userModel = $this->servletFactory->userServ()->getUserProfileByFields(["email" => trim($userProfile["email"])]);

        if (!$this->isEqualByPassword($userModel->getAttr("password"), trim($userProfile["password"]))) {
            throw new ParameterException(["errMessage" => "用户名或者密码错误..."]);
        }

        $store = $userModel->store()->where("status", 1)->find();

        if (is_null($store)) {
            throw new ParameterException(["errMessage" => "店铺功能暂未通过..."]);
        }

        $accessToken = JWTAuth::builder(["storeID" => $store->id]);
        return renderResponse(compact("accessToken"));
    }

    /**
     * isEqualByPassword
     * @param string $origin
     * @param string $input
     * @return bool
     */
    protected function isEqualByPassword(string $origin, string $input): bool
    {
        return password_verify($input, $origin);
    }

    /**
     * getStoreBaseInfo
     * @return \think\response\Json
     */
    public function getStoreBaseInfo()
    {
        $storeModel = app()->get("storeProfile");

        $storeBaseInfo = [
            "storeName" => $storeModel->storeName,
            "email" => $storeModel->user->email,
            "logo" => $storeModel->user->storeLogo,
            "inviteCode" => $storeModel->inviteCode,
            "mobile" => $storeModel->mobile,
            "cardID" => $storeModel->cardID,
            "remark" => $storeModel->storeRemark,
        ];

        return renderResponse($storeBaseInfo);
    }

    /**
     * saveStoreBaseInfo
     * @param array $storeProfile
     * @return \think\response\Json
     */
    public function saveStoreBaseInfo(array $storeProfile)
    {
        $storeModel = app()->get("storeProfile");
        $storeModel->where("id", $storeModel->id)->save($storeProfile);
        return renderResponse();
    }

    /**
     * getStoreList
     * @return \think\response\Json
     */
    public function getStoreList()
    {
        $storeID = app()->get("storeProfile")->id;
        $storeList = $this->servletFactory->storeServ()->getStoreList($storeID);

        return renderPaginateResponse($storeList);
    }

    /**
     * getStoreTreeList
     * @return \think\response\Json
     */
    public function getStoreTreeList()
    {
        $storeID = app()->get("storeProfile")->id;
        $storeList = $this->servletFactory->storeServ()->getStoreTreeList($storeID);

        return renderResponse(assertTreeDatum($storeList));
    }
}