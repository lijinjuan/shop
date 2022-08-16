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
     * getPlatformGoodsList
     * @return \think\Paginator
     */
    public function getPlatformGoodsList()
    {
        return $this->goodsModel->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])->order("goodsSalesAmount", "desc")->paginate();
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
        return $this->goodsModel->where("status", 1)->where("isRecommend", 1)->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])->order($order)->paginate();
    }

    /**
     * getGoodsListByCategoryID
     * @param int $categoryID
     * @return \think\Paginator
     */
    public function getGoodsListByCategoryID(int $categoryID)
    {
        return $this->goodsModel->where("status", 1)->where("categoryID", $categoryID)->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])->order("goodsSalesAmount", "desc")->paginate();
    }

    /**
     * searchGoodsListByKeyWords
     * @param string $keywords
     * @return \think\Paginator
     */
    public function searchGoodsListByKeyWords(string $keywords)
    {
        return $this->goodsModel->where("status", 1)->whereLike("goodsName", "%$keywords%")->field(["id", "goodsName", "goodsImg", "goodsCover", "goodsPrice", "status", "goodsDiscountPrice", "commission", "goodsSalesAmount", "createdAt"])->order("goodsSalesAmount", "desc")->paginate();
    }
}