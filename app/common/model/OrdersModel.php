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



}