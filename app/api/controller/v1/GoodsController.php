<?php

namespace app\api\controller\v1;

use app\api\repositories\GoodsRepositories;
use think\Request;

/**
 * \app\api\controller\v1\GoodsController
 */
class GoodsController
{

    /**
     * @var \app\api\repositories\GoodsRepositories
     */
    protected GoodsRepositories $goodsRepositories;

    /**
     * @param \app\api\repositories\GoodsRepositories $goodsRepositories
     */
    public function __construct(GoodsRepositories $goodsRepositories)
    {
        $this->goodsRepositories = $goodsRepositories;
    }

    /**
     * getPlatformGoods
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function getPlatformGoods(Request $request)
    {
        $keywords = $request->param("keywords", "");
        return $this->goodsRepositories->getPlatformGoodsList($keywords);
    }

    /**
     * getPlatformGoodsListByItem
     * @param string $itemType
     * @return \think\response\Json
     */
    public function getPlatformGoodsListByItem(string $itemType)
    {
        return $this->goodsRepositories->getPlatformGoodsListByItem($itemType);
    }

    /**
     * getPlatformGoodsListByRecommended
     * @return \think\response\Json
     */
    public function getPlatformGoodsListByRecommended()
    {
        return $this->goodsRepositories->getPlatformGoodsListByRecommended();
    }

    /**
     * getGoodsListByHomeCategoryID
     * @param int $categoryID
     * @return \think\response\Json
     */
    public function getGoodsListByHomeCategoryID(int $categoryID)
    {
        return $this->goodsRepositories->getGoodsListByHomeCategoryID($categoryID);
    }

    /**
     * getGoodsListByCategoryID
     * @param int $categoryID
     * @return \think\response\Json
     */
    public function getGoodsListByCategoryID(int $categoryID, Request $request)
    {
        $keywords = (string)$request->param("keywords", "");
        return $this->goodsRepositories->getGoodsListByCategoryID($categoryID, $keywords);
    }

    /**
     * getGoodsListByKeywords
     * @param \think\Request $request
     * @return mixed
     */
    public function getGoodsListByKeywords(Request $request)
    {
        $keywords = $request->param("keywords", "");
        return $this->goodsRepositories->getGoodsListByKeywords($keywords);
    }

    /**
     * getGoodsDetailsByGoodsID
     * @param int $goodsID
     * @return mixed
     */
    public function getGoodsDetailsByGoodsID(int $goodsID)
    {
        return $this->goodsRepositories->getGoodsDetailsByGoodsID($goodsID);
    }

    /**
     * getGoodsListByExcellent
     * @return \think\response\Json
     */
    public function getGoodsListByExcellent()
    {
        return $this->goodsRepositories->getGoodsListByExcellent();
    }

    /**
     * takeDownStoreGoods
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function takeDownStoreGoods(Request $request)
    {
        $goodsID = (int)$request->param("goodsID");
        return $this->goodsRepositories->takeDownStoreGoods($goodsID);
    }

    /**
     * onSaleGoods2Store
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function onSaleGoods2Store(Request $request)
    {
        $goodsID = (int)$request->param("goodsID");
        return $this->goodsRepositories->onSaleGoods2Store($goodsID);
    }
}