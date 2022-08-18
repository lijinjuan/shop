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
        $model->setAttr("inviteCode", "1234");
        return true;
    }

    /**
     * @param $value
     * @param $data
     * @return string
     */
    public function getStatusNameAttr($value,$data)
    {
        $status = [
            1=>'正常',
            2=>'冻结'
        ];
        return $status[$data['status']];
    }

    /**
     * @param $value
     * @param $data
     * @return int
     */
    public function getParentIDAttr($value, $data)
    {
        if ($data["agentParentID"] == ",")
            return 0;

        $agentParentID = trim($data["agentParentID"], ",");
        $parentArr = explode(",", $agentParentID);
        return (int)end($parentArr);
    }
}