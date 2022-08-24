<?php

namespace app\common\model;

use think\Model;

class RefundModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_orders_refund";

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
     * goods
     * @return \think\model\relation\HasOne
     */
    public function goods()
    {
        return $this->hasOne(GoodsModel::class, "id", "goodsID");
    }

    /**
     * orderDetails
     * @return \think\model\relation\BelongsTo
     */
    public function orderDetails()
    {
        return $this->belongsTo(OrdersDetailModel::class, "orderID", "id");
    }

}