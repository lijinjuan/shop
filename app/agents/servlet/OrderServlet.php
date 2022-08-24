<?php

namespace app\agents\servlet;

use app\common\model\OrdersModel;

class OrderServlet
{
    /**
     * @var OrdersModel
     */
    protected OrdersModel $ordersModel;

    /**
     * @param OrdersModel $ordersModel
     */
    public function __construct(OrdersModel $ordersModel)
    {
        $this->ordersModel = $ordersModel;
    }

    /**
     * @param int $pageSize
     * @param array $conditions
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function orderList(int $pageSize, array $conditions)
    {
        $model = $this->ordersModel->where('agentID', 'like', '%,' . app()->get("agentProfile")->id . ',%');
        if (isset($conditions['status'])) {
            $model->where('orderStatus', $conditions['status'] - 1);
        }
        if (!empty($conditions['orderNo'])) {
            $model->where('orderNo', $conditions['orderNo']);
        }
        if (!empty($conditions['received'])) {
            $model->where('receiver', $conditions['received']);
        }
        if (!empty($conditions['startTime'])) {
            $model->where('createdAt', '>=', $conditions['startTime']);
        }
        if (!empty($conditions['endTime'])) {
            $model->where('createdAt', '<=', $conditions['endTime']);
        }
        //以下两个搜索是我从业以来写的最傻叉的搜索条件，不要问我为什么，产品要这样做。。。
        if (!empty($conditions['storeID'])) {
            $model->where('storeID', 'like', '%' . $conditions['storeID'] . '%');
        }
        if (!empty($conditions['userID'])) {
            $model->where('userID', 'like', '%' . $conditions['userID'] . '%');
        }

        return $model->with(['goodsDetail', 'user' => function ($query) {
            $query->field(['id', 'userName', 'balance']);
        }])->paginate($pageSize);
    }

    /**
     * @param array $orderID
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderShip(array $orderID)
    {
        $orderInfo = $this->ordersModel->whereIn('id', $orderID)->select();
        foreach ($orderInfo as $item) {
            $item::update(['orderStatus' => 3, 'deliverAt' => date('Y-m-d H:i:s')], ['id' => $orderID]);
            $item->goodsDetail()->update(['status' => 3, 'updatedAt' => date('Y-m-d H:i:s')]);
        }
        return true;
    }

    /**
     * @param int $status
     * @return int
     * @throws \think\db\exception\DbException
     */
    public function orderStatistics(int $status = 0)
    {
        if ($status) {
            $count = $this->ordersModel->where('agentID', 'like', '%,' . app()->get('agentProfile')->id . ',%')->where('orderStatus', $status - 1)->sum('userPayPrice');
        } else {
            $count = $this->ordersModel->where('agentID', 'like', '%,' . app()->get('agentProfile')->id . ',%')->sum('userPayPrice');
        }
        return $count;
    }

    /**
     * @param $type
     * @return float|int
     */
    public function orderStatisticsByType($type)
    {
        $count = 0;
        if ($type == 'today') {
            $beginTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d", time())));
            $endTime = date("Y-m-d H:i:s", strtotime(date("Y-m-d", time())) + 60 * 60 * 24);
            $count = $this->ordersModel->where('agentID', 'like', '%,' . app()->get('agentProfile')->id . ',%')->where('createdAt', '>=', $beginTime)->where('createdAt', '<', $endTime)->sum('userPayPrice');
        } elseif ($type == 'month') {
            $beginTime = date("Y-m-01", time());
            $endTime = date("Y-m-t", time());
            $count = $this->ordersModel->where('agentID', 'like', '%,' . app()->get('agentProfile')->id . ',%')->where('createdAt', '>=', $beginTime)->where('createdAt', '<', $endTime)->sum('userPayPrice');
        }

        return $count;
    }

    /**
     * @param int $status
     * @return int
     * @throws \think\db\exception\DbException
     */
    public function orderNumStatistics(int $status)
    {
        return $this->ordersModel->where('agentID', 'like', '%,' . app()->get('agentProfile')->id . ',%')->where('orderStatus', $status - 1)->count();
    }


}