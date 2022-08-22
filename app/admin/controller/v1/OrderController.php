<?php

namespace app\admin\controller\v1;

use app\admin\repositories\OrderRepositories;

/**
 * \app\admin\controller\v1\OrderController
 */
class OrderController
{
    /**
     * @var \app\admin\repositories\OrderRepositories
     */
    protected OrderRepositories $orderRepositories;

    /**
     * @param \app\admin\repositories\OrderRepositories $orderRepositories
     */
    public function __construct(OrderRepositories $orderRepositories)
    {
        $this->orderRepositories = $orderRepositories;
    }

    /**
     * getOrderListByPaginate
     * @return \think\response\Json
     */
    public function getOrderListByPaginate()
    {
        return $this->orderRepositories->getOrderListByPaginate();
    }

    /**
     * getStoreInfoByOrderNo
     * @param string $orderNo
     */
    public function getStoreInfoByOrderNo(string $orderNo)
    {
        return $this->orderRepositories->getStoreInfoByOrderNo($orderNo);
    }
    
}