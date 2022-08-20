<?php

namespace app\admin\servlet;

use app\common\model\RechargeModel;

class RechargeServet
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
     * @param int $id
     * @return float
     */
    public function getRechargeByID(int $id)
    {
        return $this->rechargeModel->where('id',$id)->where('status',1)->sum('rechargeMoney');
    }

}