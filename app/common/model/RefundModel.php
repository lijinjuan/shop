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
}