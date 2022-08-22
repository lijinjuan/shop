<?php

namespace app\admin\repositories;

use app\lib\exception\ParameterException;

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

    /**
     * ship2OrderByOrderNo
     * @param string $orderNo
     * @return \think\response\Json
     */
    public function ship2OrderByOrderNo(string $orderNo)
    {
        /**
         * @var $orderDetail \app\common\model\OrdersModel
         */
        $orderDetail = $this->servletFactory->orderServ()->getOrderEntityByOrderNo($orderNo);

        if ($orderDetail->orderStatus != 2)
            throw new ParameterException(["errMessage" => "订单状态异常..."]);

        $orderDetail->orderStatus = 3;
        $orderDetail->save();

        return renderResponse();
    }


}