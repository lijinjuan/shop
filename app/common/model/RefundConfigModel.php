<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\RefundConfigModel
 */
class RefundConfigModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_refund_config";

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