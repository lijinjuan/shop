<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\BrandsModel
 */
class BrandsModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_brands";

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