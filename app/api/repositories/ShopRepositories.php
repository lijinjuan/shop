<?php

namespace app\api\repositories;

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
        $storeInfo = $this->servletFactory->shopServ()->getStoreByBasicStatistics();
        return renderResponse($storeInfo);
    }

    /**
     * apply2OpenStore
     * @param array $shopInfo
     * @return \think\response\Json
     */
    public function apply2OpenStore(array $shopInfo)
    {
        $this->servletFactory->shopServ()->apply2CreateStore($shopInfo);
        return renderResponse();
    }

    /**
     * getGoodsListByMyStore
     * @return \think\response\Json
     */
    public function getGoodsListByMyStore()
    {
        $storeGoodsList = $this->servletFactory->shopServ()->getGoodsListByMyStore();
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
            ->hidden(["pivot", "updatedAt", "deletedAt", "brandID", "goodsContent", "goodsStock", "isRank", "isNew", "isItem"])->paginate();

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