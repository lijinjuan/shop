<?php

namespace app\admin\servlet;

use app\common\model\AgentsModel;
use app\lib\exception\ParameterException;

class AgentsServlet
{
    /**
     * @var AgentsModel
     */
    protected AgentsModel $agentsModel;

    /**
     * @param AgentsModel $agentsModel
     */
    public function __construct(AgentsModel $agentsModel)
    {
        $this->agentsModel = $agentsModel;
    }

    /**
     * @param int $pageSize
     * @param string $keywords
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function agentList(int $pageSize, string $keywords = '')
    {
        $select = ['id', 'agentAccount', 'agentName', 'loginIP', 'lastLoginAt', 'loginNum', 'status', 'createdAt', 'agentParentID'];
        $model = $this->agentsModel->where('id', '>', 0);
        if ($keywords) {
            $model->where('agentAccount', 'like', '%' . $keywords . '%');
        }
        return $model->field($select)->append(['statusName'])->order('createdAt', 'desc')->append(['parentID'])->paginate($pageSize);


    }

    /**
     * @param string $keywords
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAgentTreeList(string $keywords = '')
    {
        $model = $this->agentsModel->where('id', '>', 0);
        if ($keywords) {
            $model->whereLike('agentAccount', '%' . $keywords . '%');
        }
        return $model->field(["id", "agentAccount", "agentName", "agentParentID", "loginIP", "lastLoginAt", "loginNum", "status", "createdAt"])->append(["parentID", "statusName"])->select()->toArray();
    }

    /**
     * getAgentsProfileByFields
     * @param array $whereFields
     * @param bool $passable
     * @return \app\common\model\AgentsModel
     */
    public function getAgentsProfileByFields(array $whereFields, bool $passable = true): AgentsModel
    {
        $user = $this->agentsModel->where($whereFields)->hidden(['deletedAt'])->find();

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
            throw new ParameterException(["errMessage" => "?????????????????????..."]);
        }
    }

    /**
     * @param int $id
     * @param array $data
     * @return AgentsModel
     */
    public function updateAgentByID(int $id, array $data)
    {
        return $this->agentsModel::update($data, ['id' => $id]);
    }

    /**
     * @param $id
     * @return int[]
     */
    public function agentStatistics($id)
    {
        //?????????
        //??????????????????
        //??????????????????
        //???????????????
        //??????????????????
        //??????????????????
        //???????????????
        //??????????????????
        //??????????????????
        //??????????????????
        //??????????????????
        return [
            'storeCount' => 0,
            'rechargeCount' => 0,
            'withdrawalCount' => 0,
            'orderCount' => 0,
            'receivedCount' => 0,
            'todayOrderCount' => 0,
            'monthOrderCount' => 0,
            'finishedOrderCount' => 0,
            'noReceivedOrderCount' => 0,
            'noPayOrderCount' => 0,
            'noShipOrderCount' => 0
        ];

    }


}