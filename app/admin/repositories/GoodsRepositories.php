<?php

namespace app\admin\repositories;

use think\Request;

/**
 * \app\admin\repositories\GoodsRepositories
 */
class GoodsRepositories extends AbstractRepositories
{

    /**
     * getGoodsListByPaginate
     * @param array $condition
     * @return \think\response\Json
     */
    public function getGoodsListByPaginate(array $condition)
    {
        $goodsList = $this->servletFactory->goodsServ()->getGoodsList($condition);
        return renderPaginateResponse($goodsList);
    }

    /**
     * createGoods
     * @param array $goodsInfo
     * @param array $goodsSku
     * @return \think\response\Json
     */
    public function createGoods(array $goodsInfo, array $goodsSku)
    {
        $goodsInfo["goodsPrice"] = $goodsSku[0]["skuPrice"];
        $goodsInfo["goodsDiscountPrice"] = $goodsSku[0]["skuDiscountPrice"];
        $goodsInfo["goodsDifference"] = bcsub($goodsSku[0]["skuPrice"], $goodsSku[0]["skuDiscountPrice"], 2);
        $goodsInfo["goodsImg"] = json_encode($goodsInfo["goodsImg"], JSON_UNESCAPED_UNICODE);
        $goodsInfo["goodsStock"] = (int)array_sum(array_column($goodsSku, "skuStock"));
        $goodsInfo["goodsSalesAmount"] = (int)array_sum(array_column($goodsSku, "saleAmount"));
        /**
         * @var $goodsModel \app\common\model\GoodsModel
         */
        $goodsModel = $this->servletFactory->goodsServ()->createNewGoodsEntity($goodsInfo);
        $goodsModel->goodsSku()->saveAll($goodsSku);
        return renderResponse();
    }

    /**
     * getGoodsDetailByGoodsID
     * @param int $goodsID
     * @return \think\response\Json
     */
    public function getGoodsDetailByGoodsID(int $goodsID)
    {
        $goodsDetail = $this->servletFactory->goodsServ()->getGoodsDetailsByGoodsID($goodsID);
        return renderResponse($goodsDetail);
    }

    /**
     * editGoodsDetailByGoodsID
     * @param int $goodsID
     * @param array $goodsInfo
     * @return \think\response\Json
     */
    public function editGoodsDetailByGoodsID(int $goodsID, array $goodsInfo)
    {
        /**
         * @var $goodsDetail \app\common\model\GoodsModel
         */
        $goodsDetail = $this->servletFactory->goodsServ()->getGoodsByGoodsID($goodsID);

        $goodsInfo["goodsImg"] = json_encode($goodsInfo["goodsImg"], JSON_UNESCAPED_UNICODE);

        $goodsDetail->allowField(["goodsName", "goodsCover", "brandID", "categoryID", "isRank", "isNew",
            "isItem", "goodsImg", "goodsContent"])->save($goodsInfo);

        return renderResponse();
    }

    /**
     * editGoodsSkuBySkuID
     * @param int $skuID
     * @param array $skuDetail
     * @return \think\response\Json
     */
    public function editGoodsSkuBySkuID(int $skuID, array $skuDetail)
    {
        /**
         * @var \app\common\model\GoodsSkuModel $skuModel
         */
        $skuModel = $this->servletFactory->goodsSkuServ()->getGoodsSkuDetailBySkuID($skuID);
        // 更新
        $skuModel->allowField(["skuName", "sku", "skuImg", "skuStock", "skuDiscountPrice", "skuPrice"])->save($skuDetail);

        /**
         * @var $goodsModel \app\common\model\GoodsModel
         */
        $goodsModel = $skuModel->goods;

        // 所有的规格
        $goodsSkuDetail = $goodsModel->goodsSku()->select()->toArray();

        $goodsModel->goodsPrice = $goodsSkuDetail[0]["skuPrice"];
        $goodsModel->goodsDiscountPrice = $goodsSkuDetail[0]["skuDiscountPrice"];
        $goodsModel->goodsDifference = bcsub($goodsSkuDetail[0]["skuPrice"], $goodsSkuDetail[0]["skuDiscountPrice"], 2);
        $goodsModel->goodsStock = array_sum(array_column($goodsSkuDetail, "skuStock"));
        $goodsModel->goodsSalesAmount = array_sum(array_column($goodsSkuDetail, "saleAmount"));
        $goodsModel->save();

        return renderResponse();
    }

    /**
     * deleteGoodsSkuBySkuID
     * @param int $skuID
     * @return \think\response\Json
     */
    public function deleteGoodsSkuBySkuID(int $skuID)
    {
        /**
         * @var \app\common\model\GoodsSkuModel $skuModel
         */
        $skuModel = $this->servletFactory->goodsSkuServ()->getGoodsSkuDetailBySkuID($skuID);

        /**
         * @var $goodsModel \app\common\model\GoodsModel
         */
        $goodsModel = $skuModel->goods;
        // 更新
        $skuModel->delete();
        // 所有的规格
        $goodsSkuDetail = $goodsModel->goodsSku()->select()->toArray();

        $goodsModel->goodsPrice = $goodsSkuDetail[0]["skuPrice"];
        $goodsModel->goodsDiscountPrice = $goodsSkuDetail[0]["skuDiscountPrice"];
        $goodsModel->goodsDifference = bcsub($goodsSkuDetail[0]["skuPrice"], $goodsSkuDetail[0]["skuDiscountPrice"], 2);
        $goodsModel->goodsStock = array_sum(array_column($goodsSkuDetail, "skuStock"));
        $goodsModel->goodsSalesAmount = array_sum(array_column($goodsSkuDetail, "saleAmount"));
        $goodsModel->save();

        return renderResponse();

    }
}