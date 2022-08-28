<?php

namespace app\admin\repositories;

use app\common\service\InviteServiceInterface;
use app\lib\exception\ParameterException;

class AgentRepositories extends AbstractRepositories
{

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function agentList(int $pageSize, string $keywords)
    {
        $list = $this->servletFactory->agentServ()->agentList($pageSize, $keywords);
        $list->each(function($query) use($list){
            $query->parentAgentName = '';
            if ($query->parentID){
                $agent = $this->servletFactory->agentServ()->getAgentsProfileByFields(['id'=>$query->parentID]);
                $query->parentAgentName = $agent->agentName;
            }
        });

        return renderPaginateResponse($list);
    }

    /**
     * @param $keywords
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function treeAgentList($keywords)
    {
        $agentList = $this->servletFactory->agentServ()->getAgentTreeList($keywords);
        return renderResponse(assertTreeDatum($agentList));
    }

    /**
     * createAgents
     * @param array $agentProfile
     * @return \think\response\Json
     */
    public function createAgents(array $agentProfile)
    {
        $agentProfile['agentPassword'] = password_hash($agentProfile['agentPassword'], PASSWORD_DEFAULT);
        $agentProfile["inviteCode"] = app()->get(InviteServiceInterface::class)->agentInviteCode();
        $agentProfile['agentParentID'] = ',';
        $this->servletFactory->agentServ()->createAgents($agentProfile);
        return renderResponse();
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     */
    public function getAgentInfoByID(int $id)
    {
        $agent = $this->servletFactory->agentServ()->getAgentsProfileByFields(['id' => $id]);
        if ($agent) {
            return renderResponse($agent);
        }
        throw new ParameterException(['errMessage' => '代理商不存在...']);

    }

    /**
     * @param int $id
     * @param array $data
     * @return \think\response\Json
     */
    public function editAgentByID(int $id, array $data)
    {
        $this->servletFactory->agentServ()->updateAgentByID($id, $data);
        return renderResponse();
    }

    /**
     * @return \think\response\Json
     */
    public function agentStatistics(int $id)
    {
        return renderResponse($this->servletFactory->agentServ()->agentStatistics($id));
    }
}