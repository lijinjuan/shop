<?php

namespace app\admin\servlet;

use app\common\model\StoreAccountModel;

class StoreAccountServlet
{

    /**
     * @var StoreAccountModel
     */
    protected StoreAccountModel $storeAccountModel;

    /**
     * @param StoreAccountModel $storeAccountModel
     */
    public function __construct(StoreAccountModel $storeAccountModel)
    {
        $this->storeAccountModel = $storeAccountModel;
    }

    /**
     * @param int $storeID
     * @return array
     */
    public function getStoreStatisticsByID(int $storeID)
    {
        $rechargeSum = round($this->storeAccountModel->where('storeID', $storeID)->where('type', 1)->sum('changeBalance'), 2);
        $withdrawalSum = round($this->storeAccountModel->where('storeID', $storeID)->where('type', 2)->sum('changeBalance'), 2);
        $commissionSum = round($this->storeAccountModel->where('storeID', $storeID)->where('type', 3)->sum('changeBalance'), 2);
        $extensionSum = round($this->storeAccountModel->where('storeID', $storeID)->where('type', 4)->sum('changeBalance'), 2);
        return compact('rechargeSum', 'withdrawalSum', 'commissionSum', 'extensionSum');
    }

    /**
     * @param array $data
     * @return StoreAccountModel|\think\Model
     */
    public function addStoreAccount(array $data)
    {
        return $this->storeAccountModel::create($data);
    }

    /**
     * batchSaveStoreAccount
     * @param array $storeAccount
     * @return \think\Collection
     */
    public function batchSaveStoreAccount(array $storeAccount)
    {
        return $this->storeAccountModel->saveAll($storeAccount);
    }

    /**
     * @param int $id
     * @param int $type
     * @param string $startTime
     * @param string $endTime
     * @return float
     */
    public function getCommissionByID(int $id, int $type, string $startTime = '', string $endTime = '')
    {
        $commission = $this->storeAccountModel->where('storeID', $id)->where('type', $type);
        if ($startTime && $endTime) {
            $commission->where('createdAt', '>=', $startTime)->where('createdAt', '<', $endTime);
        }
        $commissionRes = $commission->sum('changeBalance');
        return sprintf('%.2f', round($commissionRes, 2));
    }

    /**
     * ???????????????
     * @param int $id
     * @return string
     */
    public function getTotalCommissionByID(int $id)
    {
        $commission = $this->storeAccountModel->where('storeID', $id)->whereIn('type', [3, 4]);
        $commissionRes = $commission->sum('changeBalance');
        return sprintf('%.2f', round($commissionRes, 2));
    }


}