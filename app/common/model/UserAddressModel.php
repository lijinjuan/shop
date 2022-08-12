<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\UserAddressModel
 */
class UserAddressModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_users_address";

    /**
     * @var string
     */
    protected $createTime = "createdAt";

    /**
     * @var string
     */
    protected $updateTime = "updatedAt";

    /**
     * @var bool
     */
    protected $autoWriteTimestamp = true;
}