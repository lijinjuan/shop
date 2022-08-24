<?php

namespace app\common\model;

use think\Model;

class MessagesModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_messages";

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