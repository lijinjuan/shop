<?php

namespace app\common\model;

use think\Model;

class OrdersModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_orders";

    /**
     * @var string
     */
    protected $createTime = "createdAt";

    /**
     * @var string
     */
    protected $updateTime = "updatedAt";

    /**
     * @var string
     */
    protected $autoWriteTimestamp = "timestamp";

    /**
     * @return mixed
     */
    public function goodsSku()
    {
        return $this->belongsToMany(GoodsSkuModel::class, OrdersDetailModel::class, 'skuID', 'orderNo');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function goodsDetail()
    {
        return $this->hasMany(OrdersDetailModel::class, 'orderSn', 'orderSn');
    }




}