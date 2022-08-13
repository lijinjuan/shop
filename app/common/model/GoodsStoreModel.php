<?php

namespace app\common\model;

use think\model\Pivot;

/**
 * \app\common\model\GoodsStoreModel
 */
class GoodsStoreModel extends Pivot
{

    /**
     * @var string
     */
    protected $table = "s_goods_stores";

    /**
     * @var string
     */
    protected $createTime = "createdAt";

    /**
     * @var string
     */
    protected $updateTime = false;

    /**
     * @var string
     */
    protected $autoWriteTimestamp = "timestamp";
}