<?php

namespace app\store\servlet;

use app\common\model\OrdersModel;
use app\lib\exception\ParameterException;
use think\facade\Db;

/**
 * \app\store\servlet\OrdersServlet
 */
class OrdersServlet
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
     * getOrderListByStore
     * @param int $storeID
     * @return \think\Paginator
     */
    public function getOrderListByStore(int $storeID)
    {
        $condition = request()->only(["orderStatus", "orderNo", "userID", "userEmail", "startAt", "endAt"]);

        if (isset($condition["userEmail"]))
            $orderList = $this->ordersModel::hasWhere("user", [["userName", "LIKE", "%" . $condition['userEmail'] . "%"]]);
        else
            $orderList = $this->ordersModel;

        $orderList = $orderList->where("storeID", $storeID)->field(["s_orders.id", "orderNo", "userID", "goodsTotalPrice", "orderStatus", "orderCommission", "userPayAt", "s_orders.createdAt"])
            ->with(["user" => function ($query) {
                $query->field(["id", "userName", "email"]);
            }, "goodsDetail" => function ($query) {
                $query->field(["orderNo", "goodsName", "goodsPrice", "goodsNum", "skuImage"]);
            }]);

        if (isset($condition["orderStatus"]))
            $orderList->where("orderStatus", $condition["orderStatus"]);

        if (isset($condition["orderNo"]))
            $orderList->where("orderNo", $condition["orderNo"]);

        if (isset($condition["userID"]))
            $orderList->where("userID", $condition["userID"]);

        if (isset($condition["startAt"]))
            $orderList->where("createdAt", ">=", $condition["startAt"]);

        if (isset($condition["endAt"]))
            $orderList->where("createdAt", "<=", $condition["endAt"]);

        return $orderList->order("createdAt", "desc")->paginate((int)request()->param("pageSize", 20));
    }

    /**
     * merchant2PlatformOrderPay
     * @param string $orderNo
     * @return mixed
     */
    public function merchant2PlatformOrderPay(string $orderNo, \Closure $updateAdminAccount)
    {
        /**
         * @var \app\common\model\StoresModel $storeModel
         */
        $storeModel = app()->get("storeProfile");
        $orderModel = $storeModel->orders()->where("orderNo", $orderNo)->where("orderStatus", 1)->find();

        if (is_null($orderModel))
            throw new ParameterException(["errMessage" => "订单不存在或状态异常..."]);

        return Db::transaction(function () use ($storeModel, $orderModel, $updateAdminAccount) {
            // 减去订单金额
            $payAmount = max((float)bcsub($orderModel->goodsTotalPrice, $orderModel->orderCommission, 2), 0);

            $preBalance = $storeModel->user->balance;
            if ($preBalance < $payAmount)
                throw new ParameterException(["errMessage" => "店铺余额不足，请充值..."]);

            $storeModel->user->balance = bcsub($storeModel->user->balance, $payAmount, 2);
            $storeModel->user->save();
            // 更新订单状态
            $orderModel->orderStatus = 2;
            $orderModel->storePayAt = date("Y-m-d H:i:s");
            $orderModel->updatedAt = date("Y-m-d H:i:s");
            $orderModel->save();
            // 订单详情更新状态
            $orderModel->goodsDetail()->where("orderNo", $orderModel->orderNo)->update(["status" => 2, "updatedAt" => date("Y-m-d H:i:s")]);
            // 添加账变记录
            $storeModel->storeAccount()->save(["userID" => $storeModel->userID, "balance" => $preBalance, "changeBalance" => $payAmount, "action" => 2, "type" => 5, "title" => "商家支付订单"]);
            // 总平台账户变动
            $updateAdminAccount($payAmount, $storeModel, $orderModel);

            return true;
        });
    }


}