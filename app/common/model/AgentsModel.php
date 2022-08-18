<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\AgentsModel
 */
class AgentsModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_agents";

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

    /**
     * onBeforeInsert
     * @param \think\Model $model
     * @return mixed
     */
    public static function onBeforeInsert(Model $model): mixed
    {
        $model->setAttr("inviteCode", "");
    }
}