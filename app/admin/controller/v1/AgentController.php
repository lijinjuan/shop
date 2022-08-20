<?php

namespace app\admin\controller\v1;

use app\admin\repositories\AgentRepositories;
use think\Request;

class AgentController
{
    /**
     * @var AgentRepositories
     */
    protected AgentRepositories $agentRepositories;

    /**
     * @param AgentRepositories $agentRepositories
     */
    public function __construct(AgentRepositories $agentRepositories)
    {
        $this->agentRepositories = $agentRepositories;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function agentList(Request $request)
    {
        $pageSize = $request->post('pageSize',20);
        $keywords = $request->post('keywords','');
        return $this->agentRepositories->agentList($pageSize,$keywords);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function treeAgentList(Request $request)
    {
        $keywords = $request->post('keywords','');
        return $this->agentRepositories->treeAgentList($keywords);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function addAgent(Request $request)
    {
        $agentProfile = $request->only(["agentAccount", "agentName", "agentPassword"]);
        return $this->agentRepositories->createAgents($agentProfile);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     */
    public function editAgent(int $id,Request $request)
    {
        $data = $request->only(["agentName", "agentPassword"]);
        return $this->agentRepositories->editAgentByID($id,$data);

    }

    /**
     * @param int $id
     * @return \think\response\Json
     */
    public function agentStatistics(int $id)
    {
        return $this->agentRepositories->agentStatistics($id);
    }


}