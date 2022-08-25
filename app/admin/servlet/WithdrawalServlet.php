<?php

namespace app\admin\servlet;

use app\common\model\WithdrawalModel;

class WithdrawalServlet
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
     * @param int $id
     * @return float
     */
    public function getWithdrawalByID(int $id)
    {
        return $this->withdrawalModel->where('id', $id)->where('status', 1)->sum('withdrawalMoney');
    }

    /**
     * @param int $pageSize
     * @param array $conditions
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function withdralList(int $pageSize = 20, array $conditions = [])
    {
        //商户账号 提现方式 真假人
        $select = ['id', 'storeID', 'withdrawalMoney', 'withdrawalType', 'createdAt', 'status', 'agentAmount', 'refuseReason'];
        $model = $this->withdrawalModel->where('id', '>', 0);
        if (!empty($conditions['type']) && in_array($conditions['type'], [1, 2, 3])) {
            $model->where('withdrawalType', $conditions['type']);
        }
        if (!empty($conditions['status']) && in_array($conditions['status'], [1, 2, 3])) {
            $model->where('status', $conditions['status'] - 1);
        }
        if (!empty($conditions['keywords'])) {
            //钱包地址 银行卡
            $model->where('withdrawalAmount', 'like', '%' . $conditions['keywords'] . '%');
        }
        return $model->field($select)->with(['store' => function ($query) {
            $query->field(['id', 'storeName', 'isRealPeople']);
        }])->append(['withdrawalTypeName'])->paginate($pageSize);
    }

    /**
     * @param int $id
     * @return WithdrawalModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getWithdrawalInfoByID(int $id)
    {
        $select = ['id', 'withdrawalType', 'withdrawalMoney', 'refuseReason', 'status', 'userID'];
        return $this->withdrawalModel->where('id', $id)->field($select)->with(['user' => function ($query) {
            $query->field(['id', 'balance']);
        }])->append(['withdrawalTypeName'])->find();

    }

    /**
     * @param int $id
     * @return WithdrawalModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOneWithdrawal(int $id)
    {
        return $this->withdrawalModel->where('id', $id)->find();
    }

    /**
     * @param int $storeID
     * @return float
     */
    public function getStatisticsByID(int $storeID)
    {
        return $this->withdrawalModel->where('storeID',$storeID)->where('status',1)->sum('withdrawalMoney');
    }


}