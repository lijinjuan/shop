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
        return $this->rechargeModel->where('userID',app()->get('userProfile')->id)->where('status',$status)->field(['id','rechargeType','rechargeMoney','createdAt'])->append(['rechargeName'])->order('createdAt','desc')->paginate($pageSize);
    }

    /**
     * @param int $id
     * @return RechargeModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function rechargeDetail(int $id)
    {
        return $this->rechargeModel->where('id',$id)->field(['id','orderNo','rechargeMoney','createdAt','status','rechargeVoucher'])->append(['rechargeName','orderStatus'])->find();
    }


}