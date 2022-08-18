<?php

namespace app\common\model;

class WithdrawalModel
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