<?php

namespace app\common\model;

use think\Model;

class AdminsAccountModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_admins_account";

    /**
     * @var string
     */
    protected $createTime = "createdAt";


    /**
     * @var bool
     */
    protected $autoWriteTimestamp = "timestamp";

}