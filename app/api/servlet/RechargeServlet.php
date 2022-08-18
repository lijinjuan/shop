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

    /**
     * @param int $status
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function rechargeList(int $status,int $pageSize)
    {
        return $this->rechargeModel->where('userID',app()->get('userProfile')->id)->where('status',$status)->field(['id','rechargeType','rechargeMoney','createdAt'])->order('createdAt','desc')->paginate($pageSize);
    }


}