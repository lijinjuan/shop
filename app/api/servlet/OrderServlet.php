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

    /**
     * @param array $orderData
     * @return bool
     */
    public function addOrder(array $orderData)
    {
        return $this->ordersModel->save($orderData);
    }

    /**
     * @param string $orderSn
     * @return OrdersModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderDetailByID(string $orderSn)
    {
        return $this->ordersModel->where('orderNo',$orderSn)->find();
    }

    /**
     * @param string $orderSn
     * @param array $updateData
     * @return OrdersModel
     */
    public function editOrderByID(string $orderSn,array $updateData)
    {
        return $this->ordersModel::update($updateData,['orderSn'=>$orderSn]);
    }











}