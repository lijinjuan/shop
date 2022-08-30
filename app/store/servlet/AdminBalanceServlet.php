<?php

namespace app\store\servlet;

use app\common\model\AdminBalanceModel;

class AdminBalanceServlet
{
    /**
     * @var AdminBalanceModel
     */
    protected AdminBalanceModel $adminBalanceModel;

    /**
     * @param AdminBalanceModel $adminBalanceModel
     */
    public function __construct(AdminBalanceModel $adminBalanceModel)
    {
        $this->adminBalanceModel = $adminBalanceModel;
    }

    /**
     * @return AdminBalanceModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBalance()
    {
        return $this->adminBalanceModel->where('id', 1)->field(['balance'])->find();
    }

    /**
     * updateBalance
     * @param float $balance
     * @return \app\common\model\AdminBalanceModel
     */
    public function updateBalance(float $balance)
    {
        return $this->adminBalanceModel->where('id', 1)->update(["balance" => $balance]);
    }
}