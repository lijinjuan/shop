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

    public function createAgents(Request $request)
    {
        
    }

}