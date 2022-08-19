<?php

namespace app\agents\repositories;

use app\lib\exception\ParameterException;
use thans\jwt\facade\JWTAuth;

/**
 * \app\agents\repositories\AgentsRepositories
 */
class AgentsRepositories extends AbstractRepositories
{

    /**
     * userLaunch2Agents
     * @param array $agentProfile
     * @return \think\response\Json
     */
    public function userLaunch2Agents(array $agentProfile)
    {
        $agentModel = $this->servletFactory->agentsServ()->getAgentsProfileByFields(["agentAccount" => trim($agentProfile["agentAccount"])]);

        if (!$this->isEqualByPassword($agentModel->getAttr("agentPassword"), trim($agentProfile["agentPassword"]))) {
            throw new ParameterException(["errMessage" => "agentAccount or agentPassword is incorrect"]);
        }

        $accessToken = JWTAuth::builder(["agentID" => (int)$agentModel->getAttr("id")]);
        return renderResponse(compact("accessToken"));
    }

    /**
     * isEqualByPassword
     * @param string $origin
     * @param string $input
     * @return bool
     */
    protected function isEqualByPassword(string $origin, string $input): bool
    {
        //return $input == $origin;
        return password_verify($input, $origin);
    }

    /**
     * createAgents
     * @param array $agentProfile
     * @return \think\response\Json
     */
    public function createAgents(array $agentProfile)
    {
        $agentModel = $this->servletFactory->agentsServ()->getAgentsProfileByFields(['id'=>app()->get("agentProfile")->id]);
        $agentProfile['agentPassword'] = password_hash($agentProfile['agentPassword'],PASSWORD_DEFAULT);

        if ($agentModel->agentParentID == 0){
            $agentProfile['agentParentID'] = ','.app()->get("agentProfile")->id.',';
        }else{
            $agentProfile['agentParentID'] = $agentModel->agentParentID.app()->get("agentProfile")->id.',';
        }
        $this->servletFactory->agentsServ()->createAgents($agentProfile);
        return renderResponse();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function agentList(int $pageSize,string $keywords)
    {
        return renderPaginateResponse($this->servletFactory->agentsServ()->agentList($pageSize,$keywords));
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
        $agentList = $this->servletFactory->agentsServ()->getAgentTreeList(app()->get("agentProfile")->id,$keywords);
        return renderResponse(assertTreeDatum($agentList));
    }
}