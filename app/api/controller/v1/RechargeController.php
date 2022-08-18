<?php

namespace app\api\controller\v1;

use app\api\repositories\RechargeRepositories;
use think\Request;

class RechargeController
{
    /**
     * @var RechargeRepositories
     */
    protected RechargeRepositories $rechargeRepositories;

    /**
     * @param RechargeRepositories $rechargeRepositories
     */
    public function __construct(RechargeRepositories $rechargeRepositories)
    {
        $this->rechargeRepositories = $rechargeRepositories;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function addRecharge(Request $request)
    {
        $rechargeData = $request->post(['rechargeType','rechargeMoney','rechargeVoucher']);
        return $this->rechargeRepositories->addRecharge($rechargeData);
    }

}