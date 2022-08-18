<?php

namespace app\store\servlet;

use app\common\model\GoodsModel;

/**
 * \app\store\servlet\GoodsServlet
 */
class GoodsServlet
{
    /**
     * @var \app\common\model\GoodsModel
     */
    protected GoodsModel $goodsModel;

    /**
     * @param \app\common\model\GoodsModel $goodsModel
     */
    public function __construct(GoodsModel $goodsModel)
    {
        $this->goodsModel = $goodsModel;
    }

    /**
     * getGoodsList
     * @return \think\Paginator
     */
    public function getGoodsList($request)
    {
        return $this->goodsModel->field(["id", "goodsName", "goodsCover", "goodsPrice", "goodsDiscountPrice", "goodsStock", "goodsSalesAmount", "commission", "createdAt"])
            ->order("goodsSalesAmount", "desc")
            ->paginate((int)$request->param("pageSize", 20));
    }
}