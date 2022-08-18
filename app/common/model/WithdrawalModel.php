<?php

namespace app\common\model;

use think\Model;

class WithdrawalModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_users_withdrawal";

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