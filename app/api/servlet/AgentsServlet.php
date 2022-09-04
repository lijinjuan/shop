<?php

namespace app\api\servlet;

use app\common\model\AgentsModel;
use app\lib\exception\ParameterException;

/**
 * \app\api\servlet\AgentsServlet
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
     * getAgentsInfoByInviteCode
     * @param string $inviteCode
     * @return array
     */
    public function getAgentsInfoByInviteCode(string $inviteCode)
    {
        $agents = $this->agentsModel->where("status", 1)->where("inviteCode", $inviteCode)->find();
        if (is_null($agents))
            throw new ParameterException(["errMessage" => "邀请码无效..."]);

        $agentsID = ($agents?->agentParentID . $agents->id . ",") ?? ",";
        $parentsID = ",";
        $agentsName = $agents->agentName;
        return compact("agentsID", "parentsID", "agentsName");
    }

}