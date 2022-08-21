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
        return $this->rechargeModel->where('id', $id)->where('status', 1)->sum('rechargeMoney');
    }

    /**
     * @param int $id
     * @return RechargeModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRechargeInfoByID(int $id)
    {
        return $this->rechargeModel->where('id', $id)->field(['id','rechargeType','rechargeMoney','rechargeVoucher','status','refuseReason','userID'])->with(['user'=>function($query){
            $query->field(['id','balance']);
        }])->append(['RechargeName'])->find();
    }

    /**
     * @param int $id
     * @return RechargeModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOneRechargeInfoByID(int $id)
    {
        return $this->rechargeModel->where('id',$id)->find();
    }


    /**
     * @param string $keywords
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function rechargeList(string $keywords = '', int $pageSize = 20)
    {
        //商户账号?
        $select = ['id', 'orderNo',  'storeID', 'status', 'rechargeType', 'agentAccount', 'rechargeMoney', 'createdAt'];
        $model = $this->rechargeModel->field($select);
        if ($keywords) {
            //$model->
        }
        return $model->with(['store' => function ($query) {
            $query->field(['id', 'storeName', 'isRealPeople']);
        }])->paginate($pageSize);
    }

}