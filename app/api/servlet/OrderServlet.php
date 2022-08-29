<?php

namespace app\api\servlet;

use app\common\model\OrdersModel;
use app\lib\exception\ParameterException;
use think\facade\Db;


class OrderServlet
{
    /**
     * @var OrdersModel
     */
    protected OrdersModel $ordersModel;

    /**
     * @param OrdersModel $ordersModel
     */
    public function __construct(OrdersModel $ordersModel)
    {
        $this->ordersModel = $ordersModel;
    }

    /**
     * @param array $orderData
     * @return OrdersModel|\think\Model
     */
    public function addOrder(array $orderData)
    {
        return $this->ordersModel::create($orderData);
    }

    /**
     * @param string $orderSn
     * @return OrdersModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderDetailByID(string $orderSn)
    {
        return $this->ordersModel->where('orderNo', $orderSn)->find();
    }

    /**
     * @param string $orderSn
     * @param array $updateData
     * @return OrdersModel
     */
    public function editOrderByID(string $orderSn, array $updateData)
    {
        return $this->ordersModel::update($updateData, ['orderSn' => $orderSn]);
    }

    /**
     * @param int|array $status
     * @return OrdersModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderList(int|array $status)
    {
        $model = $this->ordersModel->where('userID', app()->get('userProfile')->id);
        if (is_int($status)) {
            $model->where('orderStatus', $status);
        } else {
            $model->whereIn('orderStatus', $status);
        }
        return $model->with(['goodsDetail'])->order('createdAt', 'desc')->field(['id', 'orderNo', 'goodsTotalPrice', 'goodsNum', 'orderStatus', 'createdAt'])->hidden(['goodsDetail.createdAt', 'goodsDetail.updatedAt', 'goodsDetail.userID', 'goodsDetail.skuID', 'goodsDetail.skuName'])->select();
    }


    /**
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function orderCount()
    {
        $noPay = $this->ordersModel->where('userID', app()->get('userProfile')->id)->where('orderStatus', 0)->count();
        $noDelivery = $this->ordersModel->where('userID', app()->get('userProfile')->id)->whereIn('orderStatus', [1, 2])->count();
        $noReceived = $this->ordersModel->where('userID', app()->get('userProfile')->id)->where('orderStatus', 3)->count();
        $received = $this->ordersModel->where('userID', app()->get('userProfile')->id)->where('orderStatus', 4)->count();
        $finished = $this->ordersModel->where('userID', app()->get('userProfile')->id)->where('orderStatus', 5)->count();
        $refund = $this->ordersModel->where('userID', app()->get('userProfile')->id)->whereIn('orderStatus', [6, 7])->count();
        $totalOrder = $this->ordersModel->where('userID', app()->get('userProfile')->id)->where('orderStatus', '>=', 0)->count();
        $totalOrderPrice = sprintf('%.2f', round($this->ordersModel->where('userID', app()->get('userProfile')->id)->where('orderStatus', '>=', 0)->sum('goodsTotalPrice'), 2));
        return compact('noPay', 'noDelivery', 'noReceived', 'received', 'finished', 'refund', 'totalOrder', 'totalOrderPrice');
    }

    /**
     * @param string $orderNo
     * @return OrdersModel|array|mixed|\think\Model|null
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderDetail(string $orderNo): mixed
    {
        $orderModel = $this->ordersModel->where('orderNo', $orderNo)->where('userID', app()->get('userProfile')->id)->find();
        if (!$orderModel) {
            throw new ParameterException(['errMessage' => '订单不存在...']);
        }
        return $this->ordersModel->where('orderNo', $orderNo)->with(['goodsDetail'])->field(['id', 'orderNo', 'orderStatus', 'goodsNum', 'createdAt'])->hidden(['goodsDetail.createdAt', 'goodsDetail.updatedAt', 'goodsDetail.userID', 'goodsDetail.storeID', 'goodsDetail.skuID', 'goodsDetail.skuName'])->find();
    }

    /**
     * @param int $status
     * @return OrdersModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function storeOrderList(int $status)
    {
        $model = $this->ordersModel->where('storeID', app()->get('userProfile')->store?->id)->where('orderStatus', $status);
        return $model->with(['goodsDetail'])->order('createdAt', 'desc')->field(['id', 'orderNo', 'goodsTotalPrice', 'goodsNum', 'orderStatus', 'orderCommission', 'createdAt'])->hidden(['goodsDetail.createdAt', 'goodsDetail.updatedAt', 'goodsDetail.userID', 'goodsDetail.skuID', 'goodsDetail.skuName'])->select();
    }

    /**
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function storeOrderCount()
    {
        $storeID = app()->get('userProfile')->store?->id;
        $noUserPay = $this->ordersModel->where('storeID', $storeID)->where('orderStatus', 0)->count();
        $noStorePay = $this->ordersModel->where('storeID', $storeID)->where('orderStatus', 1)->count();
        $noDelivery = $this->ordersModel->where('storeID', $storeID)->where('orderStatus', 2)->count();
        $noReceived = $this->ordersModel->where('storeID', $storeID)->where('orderStatus', 3)->count();
        $received = $this->ordersModel->where('storeID', $storeID)->where('orderStatus', 4)->count();
        $refund = $this->ordersModel->where('storeID', $storeID)->whereIn('orderStatus', [6, 7])->count();
        $finished = $this->ordersModel->where('storeID', $storeID)->where('orderStatus', 5)->count();
        $totalOrder = $this->ordersModel->where('storeID', $storeID)->where('orderStatus', '>=', 0)->count();
        $totalOrderPrice = sprintf('%.2f', round($this->ordersModel->where('storeID', $storeID)->where('orderStatus', '>=', 0)->sum('goodsTotalPrice'), 2));
        return compact('noUserPay', 'noStorePay', 'noDelivery', 'noReceived', 'received', 'refund', 'finished', 'totalOrder', 'totalOrderPrice');
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
        $storeModel = app()->get("userProfile")->store;

        if (is_null($storeModel))
            throw new ParameterException(["errMessage" => "店铺不存在或者被删除..."]);

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