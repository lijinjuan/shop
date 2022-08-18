<?php

namespace app\api\servlet;

use app\common\model\RechargeModel;

class RechargeServlet
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
     * @param array $data
     * @return RechargeModel|\think\Model
     */
    public function addRecharge(array $data)
    {
        return $this->rechargeModel::create($data);
    }


}