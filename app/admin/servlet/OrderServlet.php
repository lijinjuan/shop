<?php

namespace app\admin\servlet;

use app\common\model\OrdersModel;
use app\lib\exception\ParameterException;

/**
 * \app\admin\servlet\OrderServlet
 */
class OrderServlet
{
    /**
     * @var \app\common\model\OrdersModel
     */
    protected OrdersModel $ordersModel;

    /**
     * @param \app\common\model\OrdersModel $ordersModel
     */
    public function __construct(OrdersModel $ordersModel)
    {
        $this->ordersModel = $ordersModel;
    }

    /**
     * getOrderEntityByOrderNo
     * @param string $orderNo
     * @param bool $passable
     * @return \app\common\model\OrdersModel|array|mixed|\think\Model|null
     */
    public function getOrderEntityByOrderNo(string $orderNo, bool $passable = true)
    {
        $orderDetail = $this->ordersModel->where("orderNo", $orderNo)->find();

        if (is_null($orderDetail) && $passable) {
            throw new ParameterException(["errMessage" => "订单不存在或者已被删除..."]);
        }

        return $orderDetail;
    }

    /**
     * getOrderList
     * @return \think\Paginator
     */
    public function getOrderListByPaginate()
    {
        return $this->ordersModel->with(["user" => function ($query) {
            $query->field("id,userName");
        }, "goodsDetail" => function ($query) {
            $query->field("orderNo,goodsName,goodsPrice,skuImage,goodsNum");
        }])->field(["id", "orderNo", "userID", "storeID", "userPayPrice", "storePayPrice", "agentAmount", "receiver", "receiverMobile", "receiverAddress", "createdAt"])
            ->order("createdAt", "desc")
            ->paginate((int)request()->param("pageSize", 20));
    }
}