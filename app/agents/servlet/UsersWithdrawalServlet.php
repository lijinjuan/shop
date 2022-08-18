<?php

namespace app\agents\servlet;

use app\common\model\WithdrawalModel;

class UsersWithdrawalServlet
{
    /**
     * @var WithdrawalModel
     */
    protected WithdrawalModel $withdrawalModel;

    /**
     * @param WithdrawalModel $withdrawalModel
     */
    public function __construct(WithdrawalModel $withdrawalModel)
    {
        $this->withdrawalModel = $withdrawalModel;
    }

    /**
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function withdralList(int $pageSize)
    {
        return $this->withdrawalModel->where('agentID','like','%,' . app()->get("agentProfile")->id . ',%')->paginate($pageSize);
    }


}