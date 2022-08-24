<?php

namespace app\common\model;

use think\model\Pivot;

/**
 * \app\common\model\OrdersDetailModel
 */
class OrdersDetailModel extends Pivot
{
    /**
     * @var string
     */
    protected $table = "s_orders_detail";

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
     * refundOrder
     * @return \think\model\relation\HasOne
     */
    public function refundOrder()
    {
        return $this->hasOne(RefundModel::class, "orderID", "id");
    }

    /**
     * orders
     * @return \think\model\relation\BelongsTo
     */
    public function orders()
    {
        return $this->belongsTo(OrdersModel::class, "orderNo", "orderNo");
    }

    /**
     * store
     * @return \think\model\relation\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(StoresModel::class, "storeID", "id");
    }

}