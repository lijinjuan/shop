<?php

namespace app\store\repositories;

/**
 * \app\store\repositories\OrderRepositories
 */
class OrderRepositories extends AbstractRepositories
{

    /**
     * getStoreOrderList
     * @return \think\response\Json
     */
    public function getStoreOrderList()
    {
        $storeID = (int)app()->get("storeProfile")->id;
        $orderList = $this->servletFactory->orderServ()->getOrderListByStore($storeID);
        return renderPaginateResponse($orderList);
    }

    /**
     * order2PayByStore
     * @param string $orderNo
     */
    public function order2PayByStore(string $orderNo)
    {
        $this->servletFactory->orderServ()->merchant2PlatformOrderPay($orderNo, function ($preAmount, $storeModel, $orderModel) {

            $adminBalance = $this->servletFactory->adminBalanceServ()->getBalance()?->balance ?? 0;
            $balance = bcadd($adminBalance, $preAmount, 2);
            $this->servletFactory->adminBalanceServ()->updateBalance($balance);

            $directAgent = $this->getAgentIDByVarchar($orderModel->agentID);
            $changeLog = $this->updateAdminAccountFields($orderModel->userID, $orderModel->storeID, $storeModel->storeName,
                $directAgent, $balance, $preAmount, 1, 1);

            $changeLog["title"] = "商户支付";
            $this->servletFactory->adminAccountServ()->addAdminAccount($changeLog);
        });
        return renderResponse();
    }

    // 更新平台账户的帐变日志
    protected function updateAdminAccountFields(int $userID, int $storeID, string $storeName, int $agentID, float $balance, float $changeBalance, int $action, int $type = 1)
    {
        return ["type" => $type, "userID" => $userID, "storeID" => $storeID, "storeName" => $storeName,
            "agentID" => $agentID, "balance" => $balance, "changeBalance" => $changeBalance, "remark" => "商户支付", "action" => $action];
    }

    /**
     * getAgentIDByVarchar
     * @param string $agentsID
     * @return int
     */
    protected function getAgentIDByVarchar(string $agentsID)
    {
        $agentsID = trim($agentsID, ",");
        if ($agentsID == ",")
            return 0;
        $agentsIDArr = explode(",", $agentsID);
        return (int)end($agentsIDArr);
    }

}