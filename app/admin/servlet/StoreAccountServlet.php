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


}