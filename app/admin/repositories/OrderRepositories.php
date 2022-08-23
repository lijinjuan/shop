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
     * @param array $conditions
     * @return \think\response\Json
     */
    public function getOrderListByPaginate(array $conditions)
    {
        $orderList = $this->servletFactory->orderServ()->getOrderListByPaginate($conditions);
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
     * @param array $orderNoArr
     * @return \think\response\Json
     */
    public function ship2OrderByOrderNo(array $orderNoArr)
    {
        /**
         * @var $orderDetail \think\model\Collection
         */
        $orderDetailArr = $this->servletFactory->orderServ()->getOrderMultiEntities($orderNoArr);

        foreach ($orderDetailArr as $orderDetail) {
            if ($orderDetail->orderStatus != 2)
                continue;

            $orderDetail->orderStatus = 3;
            $orderDetail->save();
        }

        return renderResponse();
    }


}