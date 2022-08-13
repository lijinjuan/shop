<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\GoodsModel
 */
class GoodsModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_goods";

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