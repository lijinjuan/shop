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
            $model->where('orderStatus', $conditions['status']);
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

        return $model->with(['goodsDetail'])->paginate($pageSize);
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


}