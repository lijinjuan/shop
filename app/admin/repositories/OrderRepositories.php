<?php

namespace app\admin\repositories;

use app\common\model\OrdersDetailModel;
use app\common\model\OrdersModel;
use app\common\model\RefundModel;
use app\common\model\UsersModel;
use app\lib\exception\ParameterException;
use think\Collection;
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
            $payUser->storeAccount()->save($changeLog);
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
            $changeLog["title"] = "退款";
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
    protected function updateAdminAccountFields(int $userID, int $storeID, string $storeName, int $agentID, float $balance, float $changeBalance, int $action, int $type = 6)
    {
        return ["type" => $type, "userID" => $userID, "storeID" => $storeID, "storeName" => $storeName,
            "agentID" => $agentID, "balance" => $balance, "changeBalance" => $changeBalance, "remark" => "扣除金额", "action" => $action];
    }

    /**
     * confirm2CommissionOrderDetails
     * @param string $orderNo
     * @return bool|\think\response\Json
     */
    public function confirm2CommissionOrderDetails(string $orderNo)
    {
        /**
         * @var $masterOrder \app\common\model\OrdersModel
         */
        $masterOrder = $this->servletFactory->orderServ()->getOrderEntityByOrderNo($orderNo);
        if ($masterOrder->orderStatus != 4) {
            throw new ParameterException(["errMessage" => "订单状态异常..."]);
        }

        // 待分佣的订单
        $toBeCommissionOrders = $masterOrder->goodsDetail()->where("status", 4)->select();
        if ($toBeCommissionOrders->isEmpty())
            throw new ParameterException(["errMessage" => "不存在推广分佣的订单..."]);

        // 待分佣的金额
        $toBeCommissionAmount = (float)array_sum(array_column($toBeCommissionOrders->toArray(), "goodsTotalPrice"));
        // 分佣金额为 0
        if ($toBeCommissionAmount <= 0) {
            $this->updateCompleteOrderStatus($masterOrder);
            return renderResponse();
        }

        // 待分佣的店铺
        $masterOrderStore = $masterOrder?->store;
        $toBeCommissionStores = $masterOrderStore?->parentStoreID;

        // 不存在店铺或者店铺本身为根节点
        if (is_null($toBeCommissionStores) || $toBeCommissionStores == ",") {
            $this->updateCompleteOrderStatus($masterOrder);
            return renderResponse();
        }

        // 分配的比例
        $propertyAlloc = $this->servletFactory->commissionServ()->getCommissionByType(1);
        if (is_null($propertyAlloc) && $propertyAlloc->content == "")
            throw new ParameterException(["errMessage" => "推广分佣的设置出错了..."]);

        $commissionParentArr = $this->getAmountOfCommission2Parents($toBeCommissionStores, $propertyAlloc->content, $toBeCommissionAmount);

        Db::transanction(function () use ($commissionParentArr, $masterOrder, $masterOrderStore) {
            // 更新用户的余额
            $this->servletFactory->userServ()->batchUpdateUserBalance($commissionParentArr['commissionArr']);
            // 更新帐变日志
            $this->servletFactory->storeAccountServ()->batchSaveStoreAccount($commissionParentArr['commissionArr']);
            // 修改订单状态
            $this->updateCompleteOrderStatus($masterOrder);
            // 更新平台的余额
            $platformToBeDeductAmount = $commissionParentArr["platformToBeDeductAmount"];
            $platformBalanceAmount = $this->servletFactory->adminBalanceServ()->getBalance()?->balance ?? 0;

            $toBeUpdateBalance = bcsub($platformBalanceAmount, $platformToBeDeductAmount, 2);
            $this->servletFactory->adminBalanceServ()->updateBalance($toBeUpdateBalance);
            // 直接上级
            $directAgentID = $this->getDirectAgents($masterOrder->agentID);
            // 新增平台的帐变记录
            $changeLog = $this->updateAdminAccountFields($masterOrder->userID, $masterOrder->storeID, $masterOrderStore->storeName,
                (int)$directAgentID, $toBeUpdateBalance, $platformToBeDeductAmount, 2, 4);

            $changeLog["title"] = "推广分润";
            $this->servletFactory->adminAccountServ()->addAdminAccount($changeLog);
        });

        return renderResponse();
    }

    /**
     * getDirectAgents
     * @param string $agentID
     * @return false|string
     */
    protected function getDirectAgents(string $agentID)
    {
        $directAgents = trim($agentID, ",");
        if ($directAgents == "")
            return 0;

        $directAgentsArr = explode(",", $directAgents);
        return end($directAgentsArr);
    }

    // 获取父级店铺分佣的金额
    protected function getAmountOfCommission2Parents(string $parentsID, string $propertyAllocJson, float $commissionTotalAmount)
    {
        $propertyAlloc = json_decode($propertyAllocJson, true);

        if (json_last_error() != JSON_ERROR_NONE)
            throw new ParameterException(["errMessage" => "推广分佣的设置出错..."]);

        // 需要分润的上级节点
        $parentsArr = explode(",", trim($parentsID, ","));
        // 获取需要返润的用户
        $usersCollection = $this->servletFactory->userServ()->getUserConnectionByStoreID($parentsArr);
        // 获取分润的比例
        $commissionRate = $this->getCommissionRate($propertyAlloc);

        $commissionArr = [];
        $platformToBeDeductAmount = 0;
        foreach (array_reverse($parentsArr) as $level => $parentID) {
            $userModel = $this->getUserIDByUserCollection($usersCollection, $parentID);

            if (is_null($userModel))
                continue;

            $increaseAmount = (float)$this->calculateCommissionAmount($level, $commissionRate, $commissionTotalAmount);

            $commissionUserArr["userID"] = $userModel->id;
            $commissionUserArr["storeID"] = $parentID;
            $commissionUserArr["changeBalance"] = $increaseAmount;
            $commissionUserArr["balance"] = bcadd($userModel->balance, $increaseAmount, 2);
            $commissionUserArr["type"] = 4;
            $commissionUserArr["title"] = "推广分润";
            $platformToBeDeductAmount = bcadd($platformToBeDeductAmount, $commissionUserArr["changeBalance"], 2);
            $commissionArr[] = $commissionUserArr;
        }

        return compact("commissionArr", "platformToBeDeductAmount");
    }

    /**
     * calculateCommissionAmount
     * @param int $level
     * @param array $commissionRate
     * @param float $commissionTotalAmount
     * @return string
     */
    protected function calculateCommissionAmount(int $level, array $commissionRate, float $commissionTotalAmount)
    {
        return match ($level) {
            0, 1, 2 => bcmul($commissionRate[$level] / 100, $commissionTotalAmount, 2),
            default => bcmul($commissionRate[3] / 100, $commissionTotalAmount, 2),
        };
    }

    // 获取分级的分佣比例
    protected function getCommissionRate(array $propertyAlloc)
    {
        return [0 => $propertyAlloc["firstLevel"], 1 => $propertyAlloc["secondLevel"], 2 => $propertyAlloc["thirdLevel"], 3 => $propertyAlloc["fourthLevel"]];
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

    /**
     * getUserIDByUserCollection
     * @param \think\Collection $usersModel
     * @param string $parentID
     * @return mixed
     */
    protected function getUserIDByUserCollection(Collection $usersModel, string $parentID)
    {
        return $usersModel->where("store.id", $parentID)->first();
    }

    /**
     * updateCompleteOrderStatus
     * @param \app\common\model\OrdersModel $ordersModel
     * @return int
     */
    protected function updateCompleteOrderStatus(OrdersModel $ordersModel)
    {
        $ordersModel->orderStatus = 5;
        $ordersModel->save();
        return $ordersModel->goodsDetail()->where("status", 4)->update(["status" => 5]);
    }

}