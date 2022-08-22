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
     * @param array $condition
     * @return \think\Paginator|void
     */
    public function getGoodsList(array $condition)
    {
        $goodsList = $this->goodsModel->field(["id", "goodsName", "goodsCover", "goodsPrice", "goodsDiscountPrice", "goodsStock", "goodsSalesAmount", "commission", "createdAt"]);

        if (isset($condition["goodsName"]))
            $goodsList->whereLike("goodsName", "%" . $condition["goodsName"] . "%");

        return $goodsList->order("goodsSalesAmount", "desc")
            ->paginate((int)request()->param("pageSize", 20));
    }
}