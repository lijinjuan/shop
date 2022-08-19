<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\AdminsModel
 */
class AdminsModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_admins";

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
    protected $autoWriteTimestamp = "timestamp";

}