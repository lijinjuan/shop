<?php

namespace app\api\servlet;

use app\common\model\OrdersDetailModel;

class OrderDetailServlet
{
    /**
     * @var OrdersDetailModel
     */
    protected OrdersDetailModel $detailModel;


    public function __construct()
    {
        $this->detailModel = new OrdersDetailModel();
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
    public function editOrderByOrderSn(string $orderSn, array $updateData)
    {
        return $this->detailModel::update($updateData, ['orderSn' => $orderSn]);
    }

    /**
     * @param int $orderID
     * @return OrdersDetailModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDetailByID(int $orderID)
    {
        return $this->detailModel->where('id', $orderID)->where('userID', app()->get('userProfile')->id)->with(['orders' => function ($query) {
            $query->field(['id', 'orderNo', 'receiver', 'receiverMobile', 'receiverAddress']);
        }, 'refundOrder' => function ($query) {
            $query->field(['id', 'orderID', 'remark','orderSn']);
        }])->find();
    }


}