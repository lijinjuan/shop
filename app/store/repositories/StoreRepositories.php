<?php

namespace app\store\repositories;

use app\admin\servlet\contract\ServletFactoryInterface;
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
        $condition = $request->only(["type", "startAt", "endAt"]);
        /**
         * @var \app\common\model\StoresModel $storeModel
         */
        $storeModel = app()->get("storeProfile");
        $accountList = $storeModel->storeAccount();

        if (isset($condition["type"]))
            $accountList->where("type", $condition["type"]);

        if (isset($condition["startAt"]))
            $accountList->where("createdAt", ">=", $condition["startAt"]);

        if (isset($condition["endAt"]))
            $accountList->where("createdAt", "<=", $condition["endAt"]);

        $accountList = $accountList->order("createdAt", "desc")->paginate((int)$request->param("pageSize", 20));
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

    /**
     * getStoreStatistics
     * @param \app\admin\servlet\contract\ServletFactoryInterface $adminServletFactory
     * @return \think\response\Json
     */
    public function getStoreStatistics(ServletFactoryInterface $adminServletFactory)
    {
        //访客数 totalUV
        //下级店铺数 childStore
        //今日预估佣金 todayCommission 用户购买商品获取到的佣金
        //已结算佣金 totalCommission ?????
        //推广奖励 extensionMoney 我是别人的上级获取的奖励
        //提现总金额 withdrawal
        //总订单金额 totalOrderMoney
        //在途订单金额 noReceivedMoney
        //今日订单金额 todayOrderMoney
        //月订单金额 monthOrderMoney
        //已完成订单数 finishedOrderCount
        //已发货订单数 shipOrderCount
        //待支付订单数 noPayOrderCount
        //待发货订单数 noShipOrderCount
        $id = app()->get("storeProfile")->id;
        $totalUV = app()->get("storeProfile")->totalUV ?? 0;
        $childStore = app()->get("storeProfile")->childStore ?? 0;
        $beginTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d", time())));
        $endTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d", time())) + 60 * 60 * 24);
        $todayCommission = $adminServletFactory->storeAccountServ()->getCommissionByID($id, 3, $beginTime, $endTime);
        $extensionMoney = $adminServletFactory->storeAccountServ()->getCommissionByID($id, 4);
        $totalCommission = $adminServletFactory->storeAccountServ()->getTotalCommissionByID($id);
        $withdrawal = $adminServletFactory->withdrawalServ()->getStatisticsByID($id);
        $totalOrderMoney = $adminServletFactory->orderServ()->getStatisticsByStoreID($id);
        $noReceivedMoney = $adminServletFactory->orderServ()->getStatisticsByStoreID($id, 3);
        $todayOrderMoney = $adminServletFactory->orderServ()->getStatisticsByStoreID2Time($id, $beginTime, $endTime);
        $monthOrderMoney = $adminServletFactory->orderServ()->getStatisticsByStoreID2Time($id, date("Y-m-01", time()), date("Y-m-t", time()));
        $finishedOrderCount = $adminServletFactory->orderServ()->getStatisticsNumByStoreID($id, 6);
        $shipOrderCount = $adminServletFactory->orderServ()->getStatisticsNumByStoreID($id, 4);
        $noPayOrderCount = $adminServletFactory->orderServ()->getStatisticsNumByStoreID($id, 1);
        $noShipOrderCount = $adminServletFactory->orderServ()->getStatisticsNumByStoreID($id, 3);
        return renderResponse(compact('totalUV', 'childStore', 'todayCommission', 'totalCommission', 'extensionMoney', 'withdrawal', 'totalOrderMoney', 'noReceivedMoney', 'todayOrderMoney', 'monthOrderMoney', 'finishedOrderCount', 'shipOrderCount', 'noPayOrderCount', 'noShipOrderCount'));
    }
}