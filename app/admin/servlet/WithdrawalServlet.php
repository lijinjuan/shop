<?php

namespace app\admin\servlet;

use app\common\model\WithdrawalModel;

class WithdrawalServlet
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
     * @param int $id
     * @return float
     */
    public function getWithdrawalByID(int $id)
    {
        return $this->withdrawalModel->where('id',$id)->where('status',1)->sum('withdrawalMoney');
    }


}