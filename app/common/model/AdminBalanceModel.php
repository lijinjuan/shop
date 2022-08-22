<?php

namespace app\common\model;

use think\Model;

class AdminBalanceModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_admins_balance";

    /**
     * @var string
     */
    protected $createTime = "createdAt";


    /**
     * @var bool
     */
    protected $autoWriteTimestamp = "timestamp";

}