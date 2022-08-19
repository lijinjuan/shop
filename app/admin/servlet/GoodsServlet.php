<?php

namespace app\admin\servlet;

use app\common\model\GoodsModel;
use think\Request;

/**
 * \app\admin\servlet\GoodsServlet
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
     * @param \think\Request $request
     * @return \think\Paginator
     */
    public function getGoodsList(Request $request)
    {
        $goodsList = $this->goodsModel->field(["id", "goodsName", "goodsCover", "goodsPrice", "goodsDiscountPrice", "isNew", "brandID", "categoryID", "goodsStock", "goodsSalesAmount", "commission", "status", "createdAt"])
            ->with([
                "category" => fn($query) => $query->field("id,categoryName"),
                "brands" => fn($query) => $query->field("id, brandName"),
            ])->order("goodsSalesAmount", "desc")->paginate((int)$request->param("pageSize", 20));

        return $goodsList;
    }

}