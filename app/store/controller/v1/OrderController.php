<?php

namespace app\store\controller\v1;

use app\store\repositories\OrderRepositories;
use think\Request;

/**
 * \app\store\controller\v1\OrderController
 */
class OrderController
{
    /**
     * @var \app\store\repositories\OrderRepositories
     */
    protected OrderRepositories $orderRepositories;

    /**
     * @param \app\store\repositories\OrderRepositories $orderRepositories
     */
    public function __construct(OrderRepositories $orderRepositories)
    {
        $this->orderRepositories = $orderRepositories;
    }

    /**
     * getOrderListByStore
     * @return \think\response\Json
     */
    public function getOrderListByStore()
    {
        return $this->orderRepositories->getStoreOrderList();
    }

    /**
     * order2PayByStore
     * @return \think\response\Json
     */
    public function order2PayByStore(Request $request)
    {
        $orderNo = (string)$request->param("orderNo");
        return $this->orderRepositories->order2PayByStore($orderNo);
    }

}