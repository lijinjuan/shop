<?php

namespace app\api\repositories;

use app\lib\exception\ParameterException;

/**
 * \app\api\repositories\GoodsRepositories
 */
class GoodsRepositories extends AbstractRepositories
{

    /**
     * @param int $goodsID
     * @return \app\common\model\GoodsModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsDetailByGoodsID(int $goodsID)
    {
        $goodsInfo = $this->servletFactory->goodsServ()->getGoodsDetailByGoodsID($goodsID);
        return renderResponse($goodsInfo);
    }

    /**
     * getPlatformGoodsList
     * @return \think\response\Json
     */
    public function getPlatformGoodsList(string $keywords)
    {
        $categoryID = request()->param("categoryID", 0);
        $categories = [];
        if ($categoryID > 0)
            $categories = $this->servletFactory->categoryServ()->getParentCategoryList($categoryID);

        $platformGoodsList = $this->servletFactory->goodsServ()->getPlatformGoodsList($keywords, $categories);
        $myStoreGoodsID = $this->servletFactory->shopServ()->getGoodsIDsByMyStore();
        // 获取佣金
        $commission = $this->servletFactory->commissionServ()->getCommissionByType(2);

        $commissionRate = 0;
        if ($commission->content != null) {
            $commissionRateArr = json_decode($commission->content, true);
            $commissionRate = $commissionRateArr["goodsCommission"] / 100;
        }

        $platformGoodsList->each(function ($item) use ($myStoreGoodsID, $commissionRate) {
            $item["status"] = (int)in_array($item["id"], $myStoreGoodsID);
            $item["commission"] = bcmul($item["goodsDiscountPrice"], $commissionRate, 2);
        });

        return renderPaginateResponse($platformGoodsList);
    }

    /**
     * getPlatformGoodsListByItem
     * @param string $itemType
     * @return \think\response\Json
     */
    public function getPlatformGoodsListByItem(string $itemType)
    {
        $itemFields = match ($itemType) {
            "rank" => ["isRank" => 1],
            "new" => ["isNew" => 1],
            "item" => ["isItem" => 1],
            default => throw new ParameterException(["errMessage" => "参数异常..."])
        };

        $itemLimit = match ($itemType) {
            "rank" => 3,
            "new" => 6,
            "item" => 6,
            default => throw new ParameterException(["errMessage" => "参数异常..."])
        };

        $goodsList = $this->servletFactory->goodsServ()->getGoodsListByGoodsItem($itemFields, ["goodsSalesAmount" => "desc"], $itemLimit);
        return renderResponse($goodsList);
    }

    /**
     * getPlatformGoodsListByRecommended
     * @return \think\response\Json
     */
    public function getPlatformGoodsListByRecommended()
    {
        $recommendList = $this->servletFactory->goodsServ()->getGoodsListByGoodsRecommend(["goodsSalesAmount" => "desc"]);
        return renderPaginateResponse($recommendList);
    }

    /**
     * getGoodsListByCategoryID
     * @param int $categoryID
     * @param string $keywords
     * @return \think\response\Json
     */
    public function getGoodsListByCategoryID(int $categoryID, string $keywords)
    {
        $goodsList = $this->servletFactory->goodsServ()->getGoodsListByCategoryID($categoryID, $keywords);
        return renderPaginateResponse($goodsList);
    }

    /**
     * getGoodsListByKeywords
     * @param string $keywords
     * @return \think\response\Json
     */
    public function getGoodsListByKeywords(string $keywords)
    {
        $goodsList = $this->servletFactory->goodsServ()->searchGoodsListByKeyWords($keywords);
        return renderPaginateResponse($goodsList);
    }

    /**
     * getGoodsListByHomeCategoryID
     * @param int $categoryID
     * @return \think\response\Json
     */
    public function getGoodsListByHomeCategoryID(int $categoryID)
    {
        $categories = $this->servletFactory->categoryServ()->getParentCategoryList($categoryID);
        $goodsList = $this->servletFactory->goodsServ()->getGoodsListByCategoryIDs($categories);
        return renderPaginateResponse($goodsList);
    }

    /**
     * getGoodsDetailsByGoodsID
     * @param int $goodsID
     */
    public function getGoodsDetailsByGoodsID(int $goodsID)
    {
        $goodsDetail = $this->servletFactory->goodsServ()->getGoodsDetailsByGoodsID($goodsID);
        return renderResponse($goodsDetail);
    }

    /**
     * getGoodsListByExcellent
     * @return \think\response\Json
     */
    public function getGoodsListByExcellent()
    {
        $recommendList = $this->servletFactory->goodsServ()->getGoodsListByGoodsRecommendLimit12(["goodsSalesAmount" => "desc"]);
        return renderResponse($recommendList);
    }

    /**
     * takeDownStoreGoods
     * @param int $goodsID
     * @return \think\response\Json
     */
    public function takeDownStoreGoods(int $goodsID)
    {
        app()->get("userProfile")?->store->goods()->detach($goodsID);
        return renderResponse();
    }

    /**
     * onSaleGoods2Store
     * @param int $goodsID
     * @return \think\response\Json
     */
    public function onSaleGoods2Store(int $goodsID)
    {
        /**
         * @var \app\common\model\StoresModel $storeModel
         */
        $storeModel = app()->get("userProfile")->store;
        $storeModel->goods()->sync([$goodsID => ["agentID" => $storeModel->agentID]], false);
        return renderResponse();
    }
}