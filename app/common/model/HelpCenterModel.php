<?php

namespace app\common\model;

use think\Model;

class HelpCenterModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_help_center";

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