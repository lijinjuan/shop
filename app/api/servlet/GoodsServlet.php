<?php

namespace app\api\servlet;

use app\common\model\GoodsModel;

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
       return  $this->goodsModel->newQuery()->where('id',$goodsID)->where('status',1)->find();
    }

    /**
     * @param array $goodsID
     * @param array $columns
     * @return GoodsModel
     */
    public function getGoodsListByGoodsID(array $goodsID, array $columns = ['*'])
    {
        return $this->goodsModel->whereIn('id', $goodsID)->with(['goodsSku','store'])->field($columns);
    }


}