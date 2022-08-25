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
     * getOrderMultiEntities
     * @param array $orderNoArr
     * @return \app\common\model\OrdersModel[]|array|\think\Collection
     */
    public function getOrderMultiEntities(array $orderNoArr)
    {
        return $this->ordersModel->whereIn("orderNo", $orderNoArr)->select();
    }

    /**
     * getOrderListByPaginate
     * @param array $conditions
     * @return \think\Paginator
     */
    public function getOrderListByPaginate(array $conditions)
    {
        $orderList = $this->ordersModel->with(["user" => function ($query) {
            $query->field("id,userName");
        }, "goodsDetail" => function ($query) {
            $query->field("id,orderNo,goodsName,goodsPrice,skuImage,goodsNum,status");
        }])->field(["id", "orderNo", "userID", "storeID", "userPayPrice", "storePayPrice", "orderStatus", "agentAmount", "receiver", "receiverMobile", "receiverAddress", "createdAt"]);

        if (isset($conditions["orderStatus"]))
            $orderList->where("orderStatus", (int)$conditions["orderStatus"]);

        if (isset($conditions["userID"]))
            $orderList->where("userID", (int)$conditions["userID"]);

        if (isset($conditions["startAt"]))
            $orderList->where("createdAt", ">=", $conditions["startAt"]);

        if (isset($conditions["endAt"]))
            $orderList->where("createdAt", "<=", $conditions["endAt"]);

        if (isset($conditions["orderNo"]))
            $orderList->where("orderNo", $conditions["orderNo"]);

        if (isset($conditions["storeID"]))
            $orderList->where("storeID", $conditions["storeID"]);

        if (isset($conditions["receiver"]))
            $orderList->whereLike("receiver", "%" . $conditions["receiver"] . "%");

        return $orderList->order("createdAt", "desc")->paginate((int)request()->param("pageSize", 20));
    }

    /**
     * @param int $id
     * @param int $type
     * @return float
     */
    public function getStatisticsByStoreID(int $id, int $type = 0)
    {
        //总订单金额 totalOrderMoney
        //在途订单金额 noReceivedMoney

        $orderModel = $this->ordersModel->where('storeID', $id);
        if ($type) {
            $orderModel->where('orderStatus', $type);
        }
        return sprintf("%.2f", round($orderModel->sum('userPayPrice'), 2));
    }

    /**
     * @param int $id
     * @param string $startTime
     * @param string $endTime
     * @return float
     */
    public function getStatisticsByStoreID2Time(int $id, string $startTime, string $endTime)
    {
        //今日订单金额 todayOrderMoney
        //月订单金额 monthOrderMoney
        $money = $this->ordersModel->where('storeID', $id)->where('createdAt', '>=', $startTime)->where('createdAt', '<', $endTime)->sum('userPayPrice');
        return sprintf("%.2f",round($money,2));

    }

    /**
     * @param int $id
     * @param int $type
     * @return float
     * @throws \think\db\exception\DbException
     */
    public function getStatisticsNumByStoreID(int $id, int $type = 0)
    {
        //已完成订单数 finishedOrderCount
        //已发货订单数 shipOrderCount
        //待支付订单数 noPayOrderCount
        //待发货订单数 noShipOrderCount
        $orderModel = $this->ordersModel->where('storeID', $id);
        if ($type) {
            $orderModel->where('orderStatus', $type - 1);
        }
        return round($orderModel->count(), 2);
    }
}