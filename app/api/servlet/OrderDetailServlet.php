<?php

namespace app\api\servlet;

use app\common\model\OrdersDetailModel;

class OrderDetailServlet
{
    /**
     * @var OrdersDetailModel
     */
    protected OrdersDetailModel $detailModel;

    /**
     * @param OrdersDetailModel $detailModel
     */
    public function __construct(OrdersDetailModel $detailModel)
    {
        $this->detailModel = $detailModel;
    }

    /**
     * @param array $orderData
     * @return \think\Collection
     * @throws \Exception
     */
    public function addOrder(array $orderData)
    {
        return $this->detailModel->saveAll($orderData);
    }

    /**
     * @param string $orderSn
     * @param array $updateData
     * @return OrdersDetailModel
     */
    public function editOrderByOrderSn(string $orderSn,array $updateData)
    {
        return $this->detailModel::update($updateData,['orderSn'=>$orderSn]);
    }



}