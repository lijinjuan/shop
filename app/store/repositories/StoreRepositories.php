<?php

namespace app\store\repositories;

use app\lib\exception\ParameterException;
use thans\jwt\facade\JWTAuth;
use think\Request;

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

        $storeModel = $userModel->store()->where("status", 1)->find();

        if (is_null($storeModel)) {
            throw new ParameterException(["errMessage" => "店铺功能暂未通过..."]);
        }

        $accessToken = JWTAuth::builder(["storeID" => $storeModel->id]);

        $storeProfile = [
            "storeName" => $storeModel->storeName,
            "email" => $storeModel->user->email,
            "logo" => $storeModel->storeLogo,
            "inviteCode" => $storeModel->inviteCode,
            "mobile" => $storeModel->mobile,
            "cardID" => $storeModel->cardID,
            "remark" => $storeModel->storeRemark,
        ];

        return renderResponse(compact("accessToken", "storeProfile"));
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
            "logo" => $storeModel->storeLogo,
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

    /**
     * getStoreAccountList
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function getStoreAccountList(Request $request)
    {
        /**
         * @var \app\common\model\StoresModel $storeModel
         */
        $storeModel = app()->get("storeProfile");

        $accountList = $storeModel->storeAccount()->order("createdAt", "desc")->paginate((int)$request->param("pageSize", 20));

        return renderPaginateResponse($accountList);
    }

    /**
     * getStoreGoodsList
     * @param array $condition
     * @return \think\response\Json
     */
    public function getStoreGoodsList(array $condition)
    {
        /**
         * @var \app\common\model\StoresModel $storeModel
         */
        $storeModel = app()->get("storeProfile");
        $goodsEntity = $storeModel->goods()->visible(["id", "goodsName", "goodsCover", "goodsPrice", "goodsDiscountPrice", "goodsStock", "goodsSalesAmount", "commission", "createdAt"]);

        if (isset($condition["goodsName"]))
            $goodsEntity->whereLike("goodsName", "%" . $condition["goodsName"] . "%");

        $goodsList = $goodsEntity->order("goodsSalesAmount", "desc")
            ->hidden(["pivot"])
            ->paginate((int)\request()->param("pageSize", 20));

        return renderPaginateResponse($goodsList);
    }

    /**
     * takeDownStoreGoodsByGoodsID
     * @param int $goodsID
     * @return \think\response\Json
     */
    public function takeDownStoreGoodsByGoodsID(int $goodsID)
    {
        app()->get("storeProfile")->goods()->detach($goodsID);
        return renderResponse();
    }

    /**
     * alterUserPassword
     * @param string $loginPassword
     * @param string $inputPassword
     * @return \think\response\Json
     */
    public function alterUserPassword(string $loginPassword, string $inputPassword)
    {
        /**
         * @var \app\common\model\StoresModel $storeModel
         */
        $storeModel = app()->get("storeProfile");

        // 原密码
        $originPassword = $storeModel->user->password;

        if (!password_verify($loginPassword, $originPassword)) {
            throw new ParameterException(["errMessage" => "输入的原密码不正确..."]);
        }

        $storeModel->user->password = password_hash($inputPassword, PASSWORD_DEFAULT);
        $storeModel->user->save();
        return renderResponse();
    }
}