<?php

namespace app\store\repositories;

/**
 * \app\store\repositories\OrderRepositories
 */
class OrderRepositories extends AbstractRepositories
{

    /**
     * getStoreOrderList
     * @return \think\response\Json
     */
    public function getStoreOrderList()
    {
        $storeID = (int)app()->get("storeProfile")->id;
        $orderList = $this->servletFactory->orderServ()->getOrderListByStore($storeID);
        return renderPaginateResponse($orderList);
    }

    /**
     * order2PayByStore
     * @param string $orderNo
     */
    public function order2PayByStore(string $orderNo)
    {
        $this->servletFactory->orderServ()->merchant2PlatformOrderPay($orderNo);
        return renderResponse();
    }

}