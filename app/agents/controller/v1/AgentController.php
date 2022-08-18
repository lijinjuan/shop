<?php

namespace app\agents\controller\v1;

use app\agents\repositories\AgentsRepositories;
use think\Request;

/**
 * \app\agents\controller\v1\AgentController
 */
class AgentController
{

    /**
     * @var \app\agents\repositories\AgentsRepositories
     */
    protected AgentsRepositories $agentsRepositories;

    /**
     * @param \app\agents\repositories\AgentsRepositories $agentsRepositories
     */
    public function __construct(AgentsRepositories $agentsRepositories)
    {
        $this->agentsRepositories = $agentsRepositories;
    }

    /**
     * createAgents
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function createAgents(Request $request)
    {
        $agentProfile = $request->only(["agentAccount", "agentName", "agentPassword"]);
        return $this->agentsRepositories->createAgents($agentProfile);
    }

    public function agentsList(Request $request)
    {
        $request->ip();

    }

}