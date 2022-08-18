<?php

namespace app\agents\servlet;

use app\common\model\RechargeModel;

class UsersRechargeServlet
{
    /**
     * @var RechargeModel
     */
    protected RechargeModel $rechargeModel;

    /**
     * @param RechargeModel $rechargeModel
     */
    public function __construct(RechargeModel $rechargeModel)
    {
        $this->rechargeModel = $rechargeModel;
    }

    /**
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function rechargeList(int $pageSize)
    {
        return $this->rechargeModel->where('agentID','like','%,' . app()->get("agentProfile")->id . ',%')->paginate($pageSize);
    }


}