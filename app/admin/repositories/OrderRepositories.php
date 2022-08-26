<?php

namespace app\admin\repositories;

use app\api\servlet\CommissionConfigServlet;
use app\common\model\OrdersDetailModel;
use app\common\model\OrdersModel;
use app\common\model\RefundModel;
use app\common\model\UsersModel;
use app\lib\exception\ParameterException;
use think\facade\Db;
use think\model\Collection;

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

            $this->assertMessageTemplate($orderDetail->user, $orderDetail, $this->formatMessageContent());
        }

        return renderResponse();
    }

    /**
     * assertMessageTemplate
     * @param int $userID
     * @param string $content
     * @return \app\common\model\MessagesModel|\think\Model
     */
    protected function assertMessageTemplate(UsersModel $usersModel, OrdersModel $ordersModel, \Closure $getMessageContent)
    {
        $message = ["title" => "发货通知", "content" => $getMessageContent($usersModel, $ordersModel), "userID" => $usersModel->id];
        return $this->servletFactory->messageServ()->addMessage($message);
    }

    /**
     * formatMessageContent
     * @return \Closure
     */
    protected function formatMessageContent(): \Closure
    {
        return fn($user, $ordersModel) => sprintf("尊敬的用户：%s,订单号：%s 已发货，请注意查收", $user->userName, $ordersModel->orderNo);
    }

    /**
     * getOrderRefundDetail
     * @param int $orderDetailID
     * @return mixed
     */
    public function getOrderRefundDetail(int $orderDetailID)
    {

        $orderDetail = $this->servletFactory->orderDetailServ()->getOrderDetailByID($orderDetailID);

        if (!in_array($orderDetail->status, [6, 7]))
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

        if ($refundReason['status'] == 1) {
            Db::transaction(function () use ($refundDetail, $refundReason, $refundOrderDetails) {
                $this->refundOrder($refundDetail, $refundReason);
                $this->returnAmount2UserAccount($refundOrderDetails->userID, (float)$refundOrderDetails->goodsTotalPrice, $refundOrderDetails);

                $this->returnAmount2MerchantAccount($refundOrderDetails);
                $this->refund2UpdateOrderDetailStatus($refundOrderDetails);
            });
        } else {
            $this->refundOrder($refundDetail, $refundReason);
        }

        return renderResponse();
    }

    // 修改退款订单的状态
    protected function refundOrder(RefundModel $refundDetail, array $refundReason)
    {
        $refundDetail->status = $refundReason["status"];
        $refundDetail->refuseReason = $refundReason["refuseReason"] ?? "";
        $refundDetail->checkID = app()->get("adminProfile")->id;
        $refundDetail->checkName = app()->get("adminProfile")->adminName;
        $refundDetail->checkAt = date("Y-m-d H:i:s");
        $refundDetail->save();
    }

    // 返回用户订单的金额
    protected function returnAmount2UserAccount(int $userID, float $amount, OrdersDetailModel $ordersDetailModel)
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
        // 发送站内信
        $this->assertRefundMessageTemplate($payUser, $ordersDetailModel, $amount, $this->formatRefundMessageContent());
    }

    /**
     * assertRefundMessageTemplate
     * @param \app\common\model\UsersModel $usersModel
     * @param \app\common\model\OrdersModel $ordersModel
     * @param \Closure $getMessageContent
     * @return \app\common\model\MessagesModel|\think\Model
     */
    protected function assertRefundMessageTemplate(UsersModel $usersModel, OrdersDetailModel $ordersDetailModel, float $amount, \Closure $getMessageContent)
    {
        $message = ["title" => "退款通知", "content" => $getMessageContent($ordersDetailModel, $amount), "userID" => $usersModel->id];
        return $this->servletFactory->messageServ()->addMessage($message);
    }

    /**
     * formatRefundMessageContent
     * @return \Closure
     */
    protected function formatRefundMessageContent(): \Closure
    {
        return fn($ordersDetailModel, $amount) => sprintf("您的订单：%s，平台已同意退款，金额：%f。", $ordersDetailModel->orderNo, $amount);
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


        if ((!is_null($ordersDetailModel->store))) {
            $balance = bcsub($ordersDetailModel->store->user->balance, $returnAmount, 2);
            $ordersDetailModel->store->user->balance = $balance;
            $ordersDetailModel?->store?->user->save();
        }

        if ($ordersDetailModel->store && $returnAmount > 0) {
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

    public function confirm2CommissionOrderDetails(string $orderNo)
    {
        /**
         * @1 待分配的订单
         * @2 待分配的金额
         * @3 待分配的店铺
         */

        /**
         * @var $masterOrder \app\common\model\OrdersModel
         */
        $masterOrder = $this->servletFactory->orderServ()->getOrderEntityByOrderNo($orderNo);
        // 待分佣的订单
        $toBeCommissionOrders = $masterOrder->goodsDetail()->where("status", 4)->select();
        if ($toBeCommissionOrders->isEmpty())
            throw new ParameterException(["errMessage" => "不存在推广分佣的订单..."]);
        // 待分佣的金额
        $toBeCommissionAmount = (float)array_sum(array_column($toBeCommissionOrders->toArray(), "goodsTotalPrice"));
        // 分佣金额为 0
        if ($toBeCommissionAmount <= 0)
            return true;
        // 待分佣的店铺
        $toBeCommissionStores = $masterOrder?->store->parentStoreID;
        // 不存在店铺或者店铺本身为根节点
        if (is_null($toBeCommissionStores) || $toBeCommissionStores == ",")
            return true;

        // 分配的比例
        $propertyAlloc = $this->servletFactory->commissionServ()->getCommissionByType(1);

        $propertyAlloc =


        return $orderNo;
    }

    // 获取父级店铺分佣的金额
    protected function getAmountOfCommission2Parents(string $parentsID,CommissionCo)
    {

    }

    /**
     * getUserBalanceByUserID
     * @param int $userID
     * @return \think\response\Json
     */
    public function getUserBalanceByUserID(int $userID)
    {
        $userInfo = $this->servletFactory->userServ()->getUserInfoByID($userID);

        if (is_null($userInfo)) {
            throw new ParameterException(["errMessage" => "用户不存在或者已被删除..."]);
        }

        return renderResponse(["balance" => $userInfo->balance]);
    }
}