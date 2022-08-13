<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\StoresModel
 */
class StoresModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_stores";

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