<?php

namespace app\api\servlet;

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











}