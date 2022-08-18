<?php

namespace app\common\model;

use think\Model;

class UsersAmountModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_users_amount";

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