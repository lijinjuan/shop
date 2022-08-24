<?php

namespace app\admin\repositories;

use app\common\model\OrdersDetailModel;
use app\common\model\RefundModel;
use app\lib\exception\ParameterException;
use think\facade\Db;

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

        $refundOrderDetails = $refundDetail->orderDetails;

        if ($refundOrderDetails->status != 6)
            throw new ParameterException(["errMessage" => "退款的订单异常..."]);

        if ($refundOrderDetails->status == 1) {
            Db::transaction(function () use ($refundDetail, $refundReason, $refundOrderDetails) {
                $this->refundOrder($refundDetail, $refundReason);
                $this->returnAmount2UserAccount($refundOrderDetails->userID, (float)$refundOrderDetails->goodsTotalPrice);

                $this->returnAmount2MerchantAccount($refundOrderDetails);
                $this->refund2UpdateOrderDetailStatus($refundOrderDetails);
            });
        }
        
        return renderResponse();

    }

    // 修改退款订单的状态
    protected function refundOrder(RefundModel $refundDetail, array $refundReason)
    {
        $refundDetail->status = $refundReason["status"];
        $refundDetail->refuseReason = $refundReason["refuseReason"];
        $refundDetail->checkID = app()->get("adminProfile")->id;
        $refundDetail->checkName = app()->get("adminProfile")->adminName;
        $refundDetail->checkAt = date("Y-m-d H:i:s");
        $refundDetail->save();
    }

    // 返回用户订单的金额
    protected function returnAmount2UserAccount(int $userID, float $amount)
    {
        $payUser = $this->servletFactory->userServ()->getUserInfoByID($userID);

        if (is_null($payUser))
            throw new ParameterException(["errMessage" => "用户异常或者不存在..."]);

        $balance = bcadd($payUser->balance, $amount, 2);
        $payUser->balance = $balance;

        if ($amount > 0) {
            $storeID = 0;
            if (!is_null($payUser->store)) {
                $storeID = $payUser->store->id;
            }
            $changeLog = $this->update2ChangeAccountLog($storeID, (int)$payUser->id, $balance, $amount);
            $payUser->store->storeAccount()->save($changeLog);
        }

        $payUser->save();
    }

    // 商家返回用户的金额
    protected function returnAmount2MerchantAccount(OrdersDetailModel $ordersDetailModel)
    {
        // 1 减去商家的佣金
        // 2 减去全部的金额
        $originOrder = $ordersDetailModel->orders;

        if ((int)$originOrder->storePayPrice <= 0)
            $returnAmount = $ordersDetailModel->goodsTotalPrice;

        if ((int)$originOrder->storePayPrice > 0) {
            $returnAmount = $ordersDetailModel->goodsCommission;
            $agentID = 0;

            if (!is_null($ordersDetailModel->store)) {
                $agentID = $ordersDetailModel->store->parentAgentID;
            }

            $adminBalance = $this->servletFactory->adminBalanceServ()->getBalance();
            $changeBalance = bcsub($ordersDetailModel->goodsTotalPrice, (float)$ordersDetailModel->goodsCommission, 2);

            $changeLog = $this->updateAdminAccountFields($originOrder->userID, $ordersDetailModel->storeID, $ordersDetailModel->store->storeName,
                $agentID, (float)$adminBalance->balance, $changeBalance, 2);

            $this->servletFactory->adminAccountServ()->addAdminAccount($changeLog);
        }

        $balance = bcsub($ordersDetailModel->store->user->balance, $returnAmount, 2);
        $ordersDetailModel->store->user->balance = $balance;
        $ordersDetailModel->store->user->save();

        if ($returnAmount > 0) {
            $changeLog = $this->update2ChangeAccountLog($ordersDetailModel->storeID, (int)$ordersDetailModel->store->userID, $balance, $returnAmount, 2);
            $ordersDetailModel->store->storeAccount()->save($changeLog);
        }

        return true;
    }

    // 更新订单详情的状态
    protected function refund2UpdateOrderDetailStatus(OrdersDetailModel $ordersDetailModel)
    {
        $ordersDetailModel->status = 7;
        $ordersDetailModel->save();
    }

    // 更新账户的帐变日志
    protected function update2ChangeAccountLog(int $storeID, int $userID, float $balance, float $changeBalance, $action = 1, $type = 6)
    {
        return [
            "title" => "退款", "storeID" => $storeID, "balance" => $balance, "userID" => $userID,
            "changeBalance" => $changeBalance, "action" => $action, "remark" => "退款", "type" => $type
        ];
    }

    // 更新平台账户的帐变日志
    protected function updateAdminAccountFields(int $userID, int $storeID, string $storeName, int $agentID, float $balance, float $changeBalance, int $action)
    {
        return ["type" => 6, "userID" => $userID, "storeID" => $storeID, "storeName" => $storeName,
            "agentID" => $agentID, "balance" => $balance, "changeBalance" => $changeBalance, "remark" => "退款", "action" => 2];
    }
}