<?php

namespace app\common\model;

use think\Model;

class RechargeConfigModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_recharge_config";

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