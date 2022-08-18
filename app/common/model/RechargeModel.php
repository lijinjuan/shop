<?php

namespace app\common\model;

use think\Model;

class RechargeModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_users_recharge";

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