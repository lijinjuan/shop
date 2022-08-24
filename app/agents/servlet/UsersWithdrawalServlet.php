<?php

namespace app\agents\servlet;

use app\common\model\WithdrawalModel;

class UsersWithdrawalServlet
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
     * @param int $pageSize
     * @param array $conditions
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function withdralList(int $pageSize, array $conditions)
    {
        //商户账号 提现方式 真假人
        $select = ['id', 'storeID', 'withdrawalMoney', 'withdrawalType', 'createdAt', 'status', 'agentAmount', 'refuseReason'];
        $model = $this->withdrawalModel->where('agentID', 'like', '%,' . app()->get("agentProfile")->id . ',%');
        if (!empty($conditions['type'])) {
            $model->where('withdrawalType', $conditions['type']);
        }
        if (!empty($conditions['status'])) {
            $model->where('status', $conditions['status'] - 1);
        }
        if (!empty($conditions['keywords'])) {
            //钱包地址 银行卡
            $model->with(['usersAmount'=>function($query) use($conditions){
                $query->field(['userID','bankCard','walletAddress'])->where('bankCard','like','%'.$conditions['keywords'].'%')->whereOr('walletAddress','like','%'.$conditions['keywords'].'%');
            }]);
        }
        return $model->field($select)->with(['store' => function ($query) {
            $query->field(['id', 'storeName', 'isRealPeople']);
        }])->append(['withdrawalTypeName'])->paginate($pageSize);
    }

    /**
     * @return int
     * @throws \think\db\exception\DbException
     */
    public function withdrawalStatistics()
    {
        return $this->withdrawalModel->where('agentID','like','%,'.app()->get('agentProfile')->id.',%')->count();
    }


}