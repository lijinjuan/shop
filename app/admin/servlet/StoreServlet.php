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
        return $this->storesModel->where('id', $id)->find();
    }

    /**
     * 店铺统计
     * @param int $id
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function storeStatistics(int $id): array
    {
        //Todo 待完善
        //访客数
        //下级店铺数
        //今日预估佣金
        //已结算佣金
        //推广奖励
        //提现总金额
        //总订单金额
        //在途订单金额
        //今日订单金额
        //月订单金额
        //已完成订单数
        //已发货订单数
        //待支付订单数
        //待发货订单数


        $totalUV = $this->storesModel->where('id', $id)->field(['totalUV'])->count();
        $childStore = $this->storesModel->where('parentStoreID', 'like', '%,' . $id . ',%')->count();
        $todayCommission = 0.00;
        $totalCommission = 0.00;
        //推广奖励
        $extensionMoney = 0.00;
        $withdrawal = 0.00;
        $totalOrderMoney = 0.00;
        $noReceivedMoney = 0.00;
        $todayOrderMoney = 0.00;
        $monthOrderMoney = 0.00;
        $finishedOrderCount = 0;
        $shipOrderCount = 0;
        $noPayOrderCount = 0;
        $noShipOrderCount = 0;

        return compact('totalUV', 'childStore', 'todayCommission', 'totalCommission', 'extensionMoney', 'withdrawal', 'totalOrderMoney', 'noReceivedMoney', 'todayOrderMoney', 'monthOrderMoney', 'finishedOrderCount', 'shipOrderCount', 'noPayOrderCount', 'noShipOrderCount');


    }


}