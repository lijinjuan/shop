<?php

namespace app\api\controller\v1;

use app\api\repositories\OrderRepositories;
use app\common\service\InviteServiceInterface;
use think\Request;

/**
 * \app\api\controller\v1\OrderController
 */
class OrderController
{
    /**
     * @var OrderRepositories
     */
    protected OrderRepositories $orderRepositories;

    /**
     * @param OrderRepositories $orderRepositories
     */
    public function __construct(OrderRepositories $orderRepositories)
    {
        $this->orderRepositories = $orderRepositories;
    }


    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function placeOrder(Request $request)
    {
        $addressID = $request->post('addressID');
        $storeID = $request->post('storeID', 0);
        $goodsInfo = $request->post('goodsInfo', []);
        return $this->orderRepositories->placeOrder($addressID, $goodsInfo, $storeID);

    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function payment(Request $request)
    {
        $orderSn = $request->post('orderSn');
        return $this->orderRepositories->payment($orderSn);
    }

    /**
     * @param int $type
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderList(int $type)
    {
        return $this->orderRepositories->orderList($type);
    }

    /**
     * @param int $type
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refundOrderList(int $type)
    {
        return $this->orderRepositories->refundList($type);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function orderCount()
    {
        return $this->orderRepositories->orderCount();
    }

    /**
     * @param string $orderNo
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderDetail(string $orderNo)
    {
        return $this->orderRepositories->orderDetail($orderNo);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderDetailByID(int $id)
    {
        return $this->orderRepositories->orderDetailByID($id);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function orderRefund(Request $request)
    {
        $refundData = $request->post(['orderID', 'storeID', 'goodsID', 'goodsNum', 'reasonID', 'remark', 'voucherImg', 'refundType']);
        return $this->orderRepositories->orderRefund($refundData);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderRefundType()
    {
        return $this->orderRepositories->refundType(1);
    }

    /**
     * @param int $orderID
     * @return \think\Response
     * @throws \app\lib\exception\ParameterException
     */
    public function cancelOrderRefund(int $orderID)
    {
        return $this->orderRepositories->cancelRefundOrder($orderID);
    }


    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function storeOrderList(int $type)
    {
        return $this->orderRepositories->storeOrderList($type);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function storeOrderCount()
    {
        return $this->orderRepositories->storeOrderCount();
    }


    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editOrderStatus(Request $request)
    {
        $orderSn = $request->post('orderSn');
        return $this->orderRepositories->editOrderStatusByOrderSn($orderSn);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function delOrder(Request $request)
    {
        $orderSn = $request->delete('orderSn');
        return $this->orderRepositories->delOrderByOrderSn($orderSn);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRefundReason()
    {
        return $this->orderRepositories->getRefundReason();
    }


}