<?php

namespace app\api\controller\v1;

use app\api\repositories\WithdrawalRepositories;
use think\Request;

class WithdrawalController
{
    /**
     * @var WithdrawalRepositories
     */
    protected WithdrawalRepositories $withdrawalRepositories;

    /**
     * @param WithdrawalRepositories $withdrawalRepositories
     */
    public function __construct(WithdrawalRepositories $withdrawalRepositories)
    {
        $this->withdrawalRepositories = $withdrawalRepositories;
    }


    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function addWithdrawal(Request $request)
    {
        $withdrawalType = $request->post('type');
        $withdrawalMoney = $request->post('money');
        $payPassword= $request->post('payPassword');
        return $this->withdrawalRepositories->addWithdrawal(compact('withdrawalType','withdrawalMoney','payPassword'));

    }

    /**
     * @param int $type
     * @return \think\response\Json
     */
    public function getWithdrawalAmount(int $type)
    {
        return $this->withdrawalRepositories->getWithdrawalInfoByID($type);
    }

    /**
     * @param int $type
     * @return \think\response\Json
     */
    public function withdrawalList(int $type = 0)
    {
        return $this->withdrawalRepositories->withdrawalList($type);
    }

}