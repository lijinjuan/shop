<?php

namespace app\agents\servlet;

use app\common\model\AgentsModel;
use app\lib\exception\ParameterException;

/**
 * \app\agents\servlet\AgentsServlet
 */
class AgentsServlet
{

    /**
     * @var \app\common\model\AgentsModel
     */
    protected AgentsModel $agentsModel;

    /**
     * @param \app\common\model\AgentsModel $agentsModel
     */
    public function __construct(AgentsModel $agentsModel)
    {
        $this->agentsModel = $agentsModel;
    }

    /**
     * getAgentsProfileByFields
     * @param array $whereFields
     * @param bool $passable
     * @return \app\common\model\AgentsModel
     */
    public function getAgentsProfileByFields(array $whereFields, bool $passable = true): AgentsModel
    {
        $user = $this->agentsModel->where($whereFields)->find();

        if (is_null($user) && $passable) {
            throw new ParameterException(["errMessage" => "user does not exist"]);
        }

        return $user;
    }

    /**
     * createAgents
     * @param array $agentProfile
     * @return \app\common\model\AgentsModel|\think\Model
     */
    public function createAgents(array $agentProfile)
    {
        try {
            return $this->agentsModel::create($agentProfile);
        } catch (\Throwable) {
            throw new ParameterException(["errMessage" => "创建代理商失败..."]);
        }
    }

    /**
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function agentList(int $pageSize = 20, string $keywords = '')
    {
        $select = ['id', 'agentAccount', 'agentName', 'loginIP', 'lastLoginAt', 'loginNum', 'status', 'createdAt'];
        $model = $this->agentsModel->where('agentParentID', 'like', '%,' . app()->get("agentProfile")->id . ',%');
        if ($keywords) {
            $model->where('agentAccount', 'like', '%' . $keywords . '%');
        }
        return $model->field($select)->append(['statusName'])->order('createdAt', 'desc')->paginate($pageSize);
    }

    /**
     * @param int $agentID
     * @param string $keywords
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAgentTreeList(int $agentID, string $keywords = '')
    {
        $model = $this->agentsModel->whereLike("agentParentID", "%,$agentID,%");
        if ($keywords) {
            $model->whereLike('agentAccount', '%' . $keywords . '%');
        }
        return $model->field(["id", "agentAccount", "agentName", "agentParentID", "loginIP", "lastLoginAt", "loginNum", "status", "createdAt"])->append(["parentID", "statusName"])->select()->toArray();
    }
}