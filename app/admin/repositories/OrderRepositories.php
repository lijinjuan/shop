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

    /**
     * getOrderRefundDetail
     * @param int $orderDetailID
     * @return mixed
     */
    public function getOrderRefundDetail(int $orderDetailID)
    {

        $orderDetail = $this->servletFactory->orderDetailServ()->getOrderDetailByID($orderDetailID);

        if ($orderDetail->status != 6)
            throw new ParameterException(["errMessage" => "子订单状态异常..."]);

        $refundDetail = $orderDetail->refundOrder;
        $refundReason = $this->servletFactory->refundConfigServ()->getRefundReasonConfigByID($refundDetail->reasonID, 2);
        $refundDetail->refundReason = $refundReason;
        $refundDetail->refundTypeDesc = $this->servletFactory->refundConfigServ()->getRefundReasonConfigByID($refundDetail->refundType, 1);;

        return renderResponse($refundDetail->visible(["id", "goodsName", "goodsNum", "status", "voucherImg", "refuseReason", "remark"]));

    }

    /**
     * review2RefundOrderByRefundID
     * @param array $refundReason
     * @return \think\response\Json
     */
    public function review2RefundOrderByRefundID(array $refundReason)
    {
        $refundDetail = $this->servletFactory->refundServ()->getRefundDetailByID($refundReason["refundID"], 0);

        $refundDetail->status = $refundReason["status"];
        $refundDetail->refuseReason = $refundReason["refuseReason"];
        $refundDetail->checkID = app()->get("adminProfile")->id;
        $refundDetail->checkName = app()->get("adminProfile")->adminName;
        $refundDetail->checkAt = date("Y-m-d H:i:s");
        $refundDetail->save();
        return renderResponse();
    }

}