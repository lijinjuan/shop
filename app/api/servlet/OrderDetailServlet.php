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

    public function addOrder(array $orderData)
    {
        return $this->detailModel->saveAll($orderData);
    }



}