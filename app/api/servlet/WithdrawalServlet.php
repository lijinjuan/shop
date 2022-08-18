<?php

namespace app\api\servlet;

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
     * @param array $data
     * @return WithdrawalModel|\think\Model
     */
    public function addWithdrawal(array $data)
    {
       return $this->withdrawalModel::create($data);
    }


}