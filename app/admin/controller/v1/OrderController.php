<?php

namespace app\admin\controller\v1;

use app\admin\repositories\OrderRepositories;
use think\Request;

/**
 * \app\admin\controller\v1\OrderController
 */
class OrderController
{
    /**
     * @var \app\admin\repositories\OrderRepositories
     */
    protected OrderRepositories $orderRepositories;

    /**
     * @param \app\admin\repositories\OrderRepositories $orderRepositories
     */
    public function __construct(OrderRepositories $orderRepositories)
    {
        $this->orderRepositories = $orderRepositories;
    }

    /**
     * getOrderListByPaginate
     * @return \think\response\Json
     */
    public function getOrderListByPaginate(Request $request)
    {
        $conditions = $request->only(["orderStatus", "userID", "storeID", "orderNo", "receiver", "startAt", "endAt"]);
        return $this->orderRepositories->getOrderListByPaginate($conditions);
    }

    /**
     * getStoreInfoByOrderNo
     * @param string $orderNo
     */
    public function getStoreInfoByOrderNo(string $orderNo)
    {
        return $this->orderRepositories->getStoreInfoByOrderNo($orderNo);
    }

    /**
     * ship2OrderByOrderNo
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function ship2OrderByOrderNo(Request $request)
    {
        $orderNoArr = $request->param("orderNoArr");
        return $this->orderRepositories->ship2OrderByOrderNo($orderNoArr);
    }

    /**
     * getOrderRefundDetail
     * @param \think\Request $request
     * @return mixed
     */
    public function getOrderRefundDetail(Request $request)
    {
        $orderDetailID = (int)$request->param("orderDetailID");
        return $this->orderRepositories->getOrderRefundDetail($orderDetailID);
    }

    /**
     * review2RefundOrderByRefundID
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function review2RefundOrderByRefundID(Request $request)
    {
        $refundReason = $request->only(["refundID", "status", "refuseReason"]);
        return $this->orderRepositories->review2RefundOrderByRefundID($refundReason);
    }

    // 分佣
    public function confirm2Commission(string $orderNo)
    {
        return $this->orderRepositories->confirm2CommissionOrderDetails($orderNo);
    }
}