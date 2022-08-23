<?php

namespace app\admin\controller\v1;

use app\admin\repositories\GoodsRepositories;
use app\lib\exception\ParameterException;
use think\Request;

/**
 * \app\admin\controller\v1\GoodsController
 */
class GoodsController
{

    /**
     * @var \app\admin\repositories\GoodsRepositories
     */
    protected GoodsRepositories $goodsRepositories;

    /**
     * @param \app\admin\repositories\GoodsRepositories $goodsRepositories
     */
    public function __construct(GoodsRepositories $goodsRepositories)
    {
        $this->goodsRepositories = $goodsRepositories;
    }

    /**
     * getGoodsListByPaginate
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function getGoodsListByPaginate(Request $request)
    {
        $condition = $request->only(["startAt", 'endAt', "goodsName"]);
        return $this->goodsRepositories->getGoodsListByPaginate($condition);
    }

    /**
     * createGoods
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function createGoods(Request $request)
    {
        $goodsInfo = $request->only(["goodsName", "goodsCover", "brandID", "categoryID", "isRank", "isNew",
            "isItem", "goodsImg", "goodsContent"]);

        $goodsSku = $request->param("goodsSku", []);

        if (count($goodsSku) < 1)
            throw new ParameterException(["errMessage" => "规格不能为空..."]);

        return $this->goodsRepositories->createGoods($goodsInfo, $goodsSku);
    }

    /**
     * editGoodsByGoodsID
     * @param int $goodsID
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function editGoodsByGoodsID(int $goodsID, Request $request)
    {
        $goodsInfo = $request->only(["goodsName", "goodsCover", "brandID", "categoryID", "isRank", "isNew",
            "isItem", "goodsImg", "goodsContent"]);

        return $this->goodsRepositories->editGoodsDetailByGoodsID($goodsID, $goodsInfo);
    }

    /**
     * getGoodsDetailByGoodsID
     * @param int $goodsID
     * @return \think\response\Json
     */
    public function getGoodsDetailByGoodsID(int $goodsID)
    {
        return $this->goodsRepositories->getGoodsDetailByGoodsID($goodsID);
    }

    /**
     * addGoodsSkuByGoodsID
     * @param int $goodsID
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function addGoodsSkuByGoodsID(int $goodsID, Request $request)
    {
        $skuDetail = $request->only(["skuName", "sku", "skuImg", "skuStock", "skuDiscountPrice", "skuPrice", "saleAmount"]);
        return $this->goodsRepositories->addGoodsSkuByGoodsID($goodsID, $skuDetail);
    }

    /**
     * editGoodsSkuBySkuID
     * @param int $skuID
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function editGoodsSkuBySkuID(int $skuID, Request $request)
    {
        $skuDetail = $request->only(["skuName", "sku", "skuImg", "skuStock", "skuDiscountPrice", "skuPrice", "saleAmount"]);
        return $this->goodsRepositories->editGoodsSkuBySkuID($skuID, $skuDetail);
    }

    /**
     * deleteGoodsSkuBySkuID
     * @param int $skuID
     * @return mixed
     */
    public function deleteGoodsSkuBySkuID(int $skuID)
    {
        return $this->goodsRepositories->deleteGoodsSkuBySkuID($skuID);
    }
}