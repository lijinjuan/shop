<?php

namespace app\admin\servlet;

use app\common\model\StoresModel;

class StoreServlet
{
    /**
     * @var StoresModel
     */
    protected StoresModel $storesModel;

    /**
     * @param StoresModel $storesModel
     */
    public function __construct(StoresModel $storesModel)
    {
        $this->storesModel = $storesModel;
    }

    /**
     * @param int $id
     * @return StoresModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getStoreInfoByID(int $id)
    {
        return $this->storesModel->where('id', $id)->with(['user'])->find();
    }

    /**
     * 店铺统计
     * @param int $id
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function storeStatistics(int $id): array
    {
        $totalUV = $this->storesModel->where('id', $id)->field(['totalUV'])->count();
        $childStore = $this->storesModel->where('parentStoreID', 'like', '%,' . $id . ',%')->count();
        return compact('totalUV', 'childStore');
    }


    /**
     * @param int $pageSize
     * @param int $status
     * @param string $userAccount
     * @param string $agentName
     * @param string $storeName
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function storeList(int $pageSize = 20, int $status = 0, string $userAccount = '',string $agentName= '',string $storeName='')
    {
        //店铺名称 代理商账号
        $model = $this->storesModel->where('id', '>', 0);
        if (!empty($userAccount)) {
            $model->where('userEmail', 'like', '%' . $userAccount . '%');
        }
        if (!empty($storeName)){
            $model->where('storeName','like','%'.$storeName.'%');
        }
        if (!empty($agentName)){
            $model->where('agentName','like','%'.$agentName.'%');
        }
        if ($status) {
            $model->where('status', $status - 1);
        }
        return $model->with(['user'])->paginate($pageSize);
    }


}