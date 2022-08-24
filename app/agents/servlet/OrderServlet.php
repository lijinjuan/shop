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
        if (!empty($conditions['storeID'])){
            $model->where('storeID','like','%'.$conditions['storeID'].'%');
        }
        if (!empty($conditions['userID'])){
            $model->where('userID','like','%'.$conditions['userID'].'%');
        }

        return $model->with(['goodsDetail','user'=>function($query){
            $query->field(['id','userName','balance']);
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


}