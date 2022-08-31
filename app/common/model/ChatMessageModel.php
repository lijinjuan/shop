<?php

namespace app\common\model;

use think\Model;

class ChatMessageModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_chat_message";

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