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
     * @param string $keywords
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function rechargeList(int $pageSize = 20, string $keywords = '')
    {
        $select = ['id', 'orderNo', 'storeID', 'status', 'rechargeType', 'agentAccount', 'rechargeMoney', 'createdAt'];
        $model = $this->rechargeModel->where('agentID', 'like', '%,' . app()->get("agentProfile")->id . ',%')->field($select);
        if ($keywords) {
            $model->where('userEmail','like','%'.$keywords.'%');
        }
        return $this->rechargeModel->with(['store'=>function($query){
            $query->field(['id','isRealPeople','storeName']);
        }])->append(['RechargeName'])->paginate($pageSize);
    }


}