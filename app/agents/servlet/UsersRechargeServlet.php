<?php

namespace app\agents\servlet;

use app\common\model\RechargeModel;

class UsersRechargeServlet
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
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function rechargeList(int $pageSize,string $keywords)
    {
        //商户账号? 真假人
        $select = ['id','orderNo','storeID','status','rechargeType','agentAccount','rechargeMoney','createdAt'];
        $model = $this->rechargeModel->where('agentID','like','%,' . app()->get("agentProfile")->id . ',%')->field($select);
        if ($keywords){
            //$model->
        }
        return $model->with(['store'=>function($query){
            $query->field(['id','storeName','isRealPeople']);
        }])->paginate($pageSize);
    }


}