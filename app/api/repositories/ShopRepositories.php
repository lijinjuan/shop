<?php

namespace app\api\repositories;

use app\common\service\InviteServiceInterface;
use think\Collection;

/**
 * \app\api\repositories\ShopRepositories
 */
class ShopRepositories extends AbstractRepositories
{

    /**
     * getStoreByBasicInfo
     * @return \think\response\Json
     */
    public function getStoreByBasicInfo()
    {
        $storeInfo = $this->servletFactory->shopServ()->getStoreByBasicInfo();
        return renderResponse($storeInfo);
    }

    /**
     * getBasicStatisticsByStore
     * @return \think\response\Json
     */
    public function getBasicStatisticsByStore()
    {
        /**
         * @var $storeModel \app\common\model\StoresModel
         */
        $storeModel = app()->get("userProfile")->store;
        $storeInfo = $this->servletFactory->shopServ()->getStoreByBasicStatistics();
        $orderStatistics = $storeModel->orders()->field("id,orderStatus")->select();

        // 财务统计 收入 未结算
        $financial["totalWithdraw"] = 0;
        $financial["totalAmount"] = 0;
        $financial["unsetAmount"] = 0;
        $financial["todayAmount"] = 0;
        $financial["monthAmount"] = 0;
        $financial["todayIncome"] = 0;
        $financial["todayIncome"] = 0;

        $orderInfo["completeCount"] = $orderStatistics?->where("orderStatus", 5)->count() ?? 0;
        $orderInfo["pendingReceiptCount"] = $orderStatistics?->where("orderStatus", 3)->count() ?? 0;
        $orderInfo["unPayedCount"] = $orderStatistics?->where("orderStatus", 1)->count() ?? 0;
        $orderInfo["pendingShipCount"] = $orderStatistics?->where("orderStatus", 2)->count() ?? 0;
        return renderResponse(compact("storeInfo", "financial", "orderInfo"));
    }

    /**
     * apply2OpenStore
     * @param array $shopInfo
     * @return \think\response\Json
     */
    public function apply2OpenStore(array $shopInfo)
    {
        // 默认一级分类
        $upperInfo = ["agentsID" => ",", "parentsID" => ","];

        // 上级的邀请码
        $input2InviteCode = $shopInfo["inviteCode"] ?? "";
        // 获取上级
        if ($input2InviteCode != "") {
            // 邀请码类型
            $mType = substr($input2InviteCode, 0, 1);
            // 获取邀请码的类型的上级信息
            $upperInfo = match ($mType) {
                "A" => $this->servletFactory->agentServ()->getAgentsInfoByInviteCode($input2InviteCode),
                "M" => $this->servletFactory->shopServ()->getShopByInviteCode($input2InviteCode),
                default => ["agentsID" => ",", "parentsID" => ","]
            };
        }

        // 更新当前申请的店铺
        $shopInfo["inviteCode"] = app()->get(InviteServiceInterface::class)->storeInviteCode();
        $shopInfo["agentID"] = $upperInfo["agentsID"];
        $shopInfo["parentStoreID"] = $upperInfo["parentsID"];

        // 创建店铺
        $this->servletFactory->shopServ()->apply2CreateStore($shopInfo);
        return renderResponse();
    }

    /**
     * getGoodsListByMyStore
     * @param string $keywords
     * @return \think\response\Json
     */
    public function getGoodsListByMyStore(string $keywords)
    {
        $categoryID = request()->param("categoryID", 0);
        $categories = [];
        if ($categoryID > 0)
            $categories = $this->servletFactory->categoryServ()->getParentCategoryList($categoryID);

        $storeGoodsList = $this->servletFactory->shopServ()->getGoodsListByMyStore($keywords, $categories);
        return renderPaginateResponse($storeGoodsList);
    }

    /**
     * getStoreList
     * @return \think\response\Json
     */
    public function getStoreList()
    {
        $shopList = $this->servletFactory->shopServ()->getStore2List();
        return renderPaginateResponse($shopList);
    }

    /**
     * getStoreList2Limit10
     * @return \think\response\Json
     */
    public function getStoreList2Limit10()
    {
        $shopList = $this->servletFactory->shopServ()->getStore2ListLimit10();
        return renderResponse($shopList);
    }

    /**
     * getGoodsListByShopID
     * @param int $shopID
     * @return \think\response\Json
     */
    public function getGoodsListByShopID(int $shopID)
    {
        $shopModel = $this->servletFactory->shopServ()->getShopInfoByShopID($shopID);
        $shopList = $shopModel->goods()->where("s_goods.status", 1)
            ->field(["s_goods.id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "s_goods.createdAt"])
            ->hidden(["pivot", "updatedAt", "deletedAt", "brandID", "goodsContent", "goodsStock", "isRank", "isNew", "isItem"])->paginate((int)request()->param("pageSize"));

        return renderPaginateResponse($shopList);
    }

    /**
     * getShopListByKeywords
     * @param string $keywords
     * @return \think\response\Json
     */
    public function getShopListByKeywords(string $keywords)
    {
        $shopList = $this->servletFactory->shopServ()->searchShopListByKeywords($keywords);
        return renderPaginateResponse($shopList);
    }

}