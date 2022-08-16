<?php

namespace app\api\servlet;

use app\common\model\GoodsSkuModel;

class GoodsSkuServlet
{
    /**
     * @var GoodsSkuModel
     */
    protected GoodsSkuModel $goodsSkuModel;

    /**
     * @param GoodsSkuModel $goodsSkuModel
     */
    public function __construct(GoodsSkuModel $goodsSkuModel)
    {
        $this->goodsSkuModel = $goodsSkuModel;
    }


    /**
     * @param array $skuIDS
     * @return GoodsSkuModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStockByID(array $skuIDS)
    {
        return $this->goodsSkuModel->whereIn('id', $skuIDS)->field(['id', 'skuStock', 'skuDiscountPrice', 'saleAmount', 'goodsID', 'skuPrice'])->select();
    }

}