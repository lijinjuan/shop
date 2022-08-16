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
     * @return \think\response\Json
     */
    public function getPlatformGoods()
    {
        return $this->goodsRepositories->getPlatformGoodsList();
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
    public function getGoodsListByCategoryID(int $categoryID)
    {
        return $this->goodsRepositories->getGoodsListByCategoryID($categoryID);
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
}