<?php

namespace app\agents\repositories;

use app\common\service\InviteServiceInterface;
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
    public function userLaunch2Agents(array $agentProfile, string $localIP)
    {
        $agentModel = $this->servletFactory->agentsServ()->getAgentsProfileByFields(["agentAccount" => trim($agentProfile["agentAccount"])]);

        if (!$this->isEqualByPassword($agentModel->getAttr("agentPassword"), trim($agentProfile["agentPassword"]))) {
            throw new ParameterException(["errMessage" => "agentAccount or agentPassword is incorrect"]);
        }
        //增加登录IP，登录次数，最后一次登录时间
        $agentModel->where('id', $agentModel->id)->save(['loginIP' => $localIP, 'lastLoginAt' => date('Y-m-d H:i:s'), 'loginNum' => ++$agentModel->loginNum]);
        $accessToken = JWTAuth::builder(["agentID" => (int)$agentModel->getAttr("id")]);
        $agentProfile = $agentModel;
        return renderResponse(compact("accessToken", "agentProfile"));
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
        $agentModel = $this->servletFactory->agentsServ()->getAgentsProfileByFields(['id' => app()->get("agentProfile")->id]);
        $agentProfile['agentPassword'] = password_hash($agentProfile['agentPassword'], PASSWORD_DEFAULT);
        $agentProfile["inviteCode"] = app()->get(InviteServiceInterface::class)->agentInviteCode();
        $agentProfile['agentParentID'] = $agentModel->agentParentID . app()->get("agentProfile")->id . ',';
        $this->servletFactory->agentsServ()->createAgents($agentProfile);
        return renderResponse();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function agentList(int $pageSize, string $keywords)
    {
        return renderPaginateResponse($this->servletFactory->agentsServ()->agentList($pageSize, $keywords));
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
        $agentList = $this->servletFactory->agentsServ()->getAgentTreeList(app()->get("agentProfile")->id, $keywords);
        return renderResponse(assertTreeDatum($agentList));
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     */
    public function getAgentInfoByID(int $id)
    {
        $agentModel = $this->servletFactory->agentsServ()->getAgentsProfileByFields(['id' => $id]);
        if (!$agentModel) {
            throw new ParameterException(['errMessage' => '代理商不存在...']);
        }
        return renderResponse($agentModel);

    }

    /**
     * @param int $id
     * @param array $data
     * @return \think\response\Json
     * @throws ParameterException
     */
    public function editAgentByID(int $id, array $data)
    {
        $agentModel = $this->servletFactory->agentsServ()->getAgentsProfileByFields(['id' => $id]);
        if (!$agentModel) {
            throw new ParameterException(['errMessage' => '代理商不存在...']);
        }
        $agentModel::update($data, ['id' => $id]);
        return renderResponse();
    }

    /**
     * @param int $id
     * @param array $data
     * @return \think\response\Json
     * @throws ParameterException
     */
    public function changeAgentPassword(int $id, array $data)
    {
        $agentModel = $this->servletFactory->agentsServ()->getAgentsProfileByFields(['id' => $id]);
        if (!$agentModel) {
            throw new ParameterException(['errMessage' => '代理商不存在...']);
        }
        if (!password_verify($data['oldPassword'], $agentModel->agentPassword)) {
            throw new ParameterException(['errMessage' => '原密码输入错误...']);
        }
        $agentModel::update($data, ['id' => $id]);
        return renderResponse();
    }
}