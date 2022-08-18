<?php

namespace app\agents\controller\v1;

use app\agents\repositories\OrderRepositories;
use think\Request;

class OrderController
{
    /**
     * @var OrderRepositories
     */
    protected OrderRepositories $orderRepositories;

    /**
     * @param OrderRepositories $orderRepositories
     */
    public function __construct(OrderRepositories $orderRepositories)
    {
        $this->orderRepositories = $orderRepositories;
    }


    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function orderList(Request $request)
    {
        //Todo 搜索
        $pageSize = $request->post('pageSize',20);
        $status = $request->post('status');
        $orderNo = $request->post('orderNo');
        $storeID = $request->post('storeID');
        $userID = $request->post('userID');
        $received = $request->post('received');
        $startTime = $request->post('startTime');
        $endTime = $request->post('endTime');
        $conditions = compact('status', 'orderNo', 'storeID', 'userID', 'received', 'startTime', 'endTime');
        return $this->orderRepositories->orderList($pageSize, $conditions);
    }


    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderShip(Request $request)
    {
        $orderID = $request->post('orderID');
        return $this->orderRepositories->orderShip($orderID);
    }
}