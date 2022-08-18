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
        } catch (\Throwable $exception) {
            var_dump($exception->getMessage());
            throw new ParameterException(["errMessage" => "创建代理商失败..."]);
        }
    }

    public function agentList()
    {
        $this->agentsModel->where('agentParentID','like','%,'.app()->get("agentProfile")->id.',%')->select();
    }
}