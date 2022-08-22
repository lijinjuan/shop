<?php

namespace app\admin\repositories;

/**
 * \app\admin\repositories\OrderRepositories
 */
class OrderRepositories extends AbstractRepositories
{

    /**
     * getOrderListByPaginate
     * @return \think\response\Json
     */
    public function getOrderListByPaginate()
    {
        $orderList = $this->servletFactory->orderServ()->getOrderListByPaginate();
        return renderPaginateResponse($orderList);
    }

    /**
     * getStoreInfoByOrderNo
     * @param string $orderNo
     * @return \think\response\Json
     */
    public function getStoreInfoByOrderNo(string $orderNo)
    {
        /**
         * @var $orderDetail \app\common\model\OrdersModel
         */
        $orderDetail = $this->servletFactory->orderServ()->getOrderEntityByOrderNo($orderNo);

        return renderResponse($orderDetail->store);
    }


}