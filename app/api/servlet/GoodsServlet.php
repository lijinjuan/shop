<?php

namespace app\api\servlet;

use app\common\model\GoodsModel;

/**
 * \app\api\servlet\GoodsServlet
 */
class GoodsServlet
{
    /**
     * @var GoodsModel
     */
    protected GoodsModel $goodsModel;

    /**
     * @param GoodsModel $goodsModel
     */
    public function __construct(GoodsModel $goodsModel)
    {
        $this->goodsModel = $goodsModel;
    }

    /**
     * @param int $goodsID
     * @return GoodsModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsDetailByGoodsID(int $goodsID)
    {
        return $this->goodsModel->newQuery()->where('id', $goodsID)->where('status', 1)->find();
    }

    /**
     * @param array $goodsID
     * @param array $columns
     * @return GoodsModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsListByGoodsID(array $goodsID, array $columns = ['*'])
    {
        return $this->goodsModel->whereIn('id', $goodsID)->with(['goodsSku'])->field($columns)->select();
    }

    /*
     * getPlatformGoodsList
     * @return \think\Paginator
     */
    public function getPlatformGoodsList()
    {
        return $this->goodsModel->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])->order("goodsSalesAmount", "desc")->paginate((int)request()->param("pageSize", 20));
    }

    /**
     * getGoodsListByGoodsItem
     * @param array $itemFields
     * @param array $order
     * @param int $limit
     * @return \app\common\model\GoodsModel[]|array|\think\Collection
     */
    public function getGoodsListByGoodsItem(array $itemFields, array $order, int $limit)
    {
        return $this->goodsModel->where($itemFields)->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])->order($order)->limit($limit)->select();
    }


    /**
     * getGoodsListByGoodsRecommend
     * @param array $order
     * @return \think\Paginator
     */
    public function getGoodsListByGoodsRecommend(array $order)
    {
        return $this->goodsModel->where("status", 1)->where("isRecommend", 1)->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])->order($order)->paginate((int)request()->param("pageSize"));
    }

    /**
     * getGoodsListByGoodsRecommendLimit12
     * @param array $order
     * @return \app\common\model\GoodsModel[]|array|\think\Collection
     */
    public function getGoodsListByGoodsRecommendLimit12(array $order)
    {
        return $this->goodsModel->where("status", 1)->where("isRecommend", 1)->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])->order($order)->limit(12)->select();
    }

    /**
     * getGoodsListByCategoryID
     * @param int $categoryID
     * @param string $keywords
     * @return \think\Paginator
     */
    public function getGoodsListByCategoryID(int $categoryID, string $keywords)
    {
        $order = request()->only(["goodsDiscountPrice", "goodsSalesAmount"]);
        $order["goodsDiscountPrice"] ??= "asc";
        $order["goodsSalesAmount"] ??= "asc";
        return $this->goodsModel->where("status", 1)
            ->where("categoryID", $categoryID)
            ->whereLike("goodsName", "%$keywords%")
            ->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])
            ->order($order)->paginate((int)request()->param("pageSize"));
    }

    /**
     * searchGoodsListByKeyWords
     * @param string $keywords
     * @return \think\Paginator
     */
    public function searchGoodsListByKeyWords(string $keywords)
    {
        $order = request()->only(["goodsDiscountPrice", "goodsSalesAmount"]);
        $order["goodsDiscountPrice"] ??= "asc";
        $order["goodsSalesAmount"] ??= "asc";
        return $this->goodsModel->where("status", 1)
            ->whereLike("goodsName", "%$keywords%")
            ->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])
            ->order($order)->paginate((int)request()->param("pageSize"));
    }

    /**
     * getGoodsListByCategoryIDs
     * @param array $categories
     * @return \think\Paginator
     */
    public function getGoodsListByCategoryIDs(array $categories)
    {
        return $this->goodsModel->whereIn("id", $categories)->where("status", 1)->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])->order("goodsSalesAmount", "desc")->paginate((int)request()->param("pageSize"));
    }

    /**
     * getGoodsDetailsByGoodsID
     * @param int $goodsID
     * @return \app\common\model\GoodsModel|array|mixed|\think\Model|null
     */
    public function getGoodsDetailsByGoodsID(int $goodsID)
    {
        return $this->goodsModel->where("id", $goodsID)->where("status", 1)
            ->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice",
                "commission", "goodsSalesAmount", "goodsContent", "goodsStock", "goodsSalesAmount", "createdAt"])
            ->with(["goodsSku" => function ($query) {
                $query->field(["id", "goodsID", "skuName", "sku", "skuImg", "skuStock", "saleAmount", "skuDiscountPrice", "skuPrice"]);
            }])->find();
    }

}