<?php

namespace app\admin\servlet;

use app\common\model\GoodsModel;
use app\lib\exception\ParameterException;
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
     * getGoodsDetailsByGoodsID
     * @param int $goodsID
     * @param bool $passable
     * @return \app\common\model\GoodsModel|array|mixed|\think\Model|null
     */
    public function getGoodsDetailsByGoodsID(int $goodsID, bool $passable = true)
    {
        $goodsDetail = $this->goodsModel->with(["goodsSku"])->where("id", $goodsID)->where("status", 1)->find();

        if (is_null($goodsDetail) && $passable) {
            throw new ParameterException(["errMessage" => "商品不存在或者已被删除..."]);
        }

        return $goodsDetail;
    }

    /**
     * getGoodsByGoodsID
     * @param int $goodsID
     * @param bool $passable
     * @return \app\common\model\GoodsModel|array|mixed|\think\Model|null
     */
    public function getGoodsByGoodsID(int $goodsID, bool $passable = true)
    {
        $goodsDetail = $this->goodsModel->where("id", $goodsID)->find();

        if (is_null($goodsDetail) && $passable) {
            throw new ParameterException(["errMessage" => "商品不存在或者已被删除..."]);
        }

        return $goodsDetail;
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

    /**
     * createNewGoodsEntity
     * @param array $goodsInfo
     * @return \app\common\model\GoodsModel|\think\Model
     */
    public function createNewGoodsEntity(array $goodsInfo)
    {
        return $this->goodsModel::create($goodsInfo);
    }

}