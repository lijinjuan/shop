<?php

namespace app\common\model;

use think\model\Pivot;

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



}