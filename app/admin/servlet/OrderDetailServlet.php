<?php

namespace app\admin\servlet;

use app\common\model\OrdersDetailModel;
use app\lib\exception\ParameterException;

/**
 * \app\admin\servlet\OrderDetailServlet
 */
class OrderDetailServlet
{

    /**
     * @var \app\common\model\OrdersDetailModel
     */
    protected OrdersDetailModel $ordersDetailModel;

    /**
     * @param \app\common\model\OrdersDetailModel $ordersDetailModel
     */
    public function __construct()
    {
        $this->ordersDetailModel = new OrdersDetailModel();
    }

    /**
     * getOrderDetailByID
     * @param int $orderDetailID
     * @param bool $passable
     * @return \app\common\model\OrdersDetailModel|array|mixed|\think\Model|null
     * @return \app\common\model\OrdersDetailModel|array|mixed|\think\Model|null
     */
    public function getOrderDetailByID(int $orderDetailID, bool $passable = true)
    {
        $orderDetail = $this->ordersDetailModel->where("id", $orderDetailID)->find();

        if (is_null($orderDetail) && $passable) {
            throw new ParameterException(["errMessage" => "子订单不存在或者已被删除..."]);
        }

        return $orderDetail;
    }

}