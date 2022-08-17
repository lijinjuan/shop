<?php

namespace app\api\controller\v1;

use app\api\repositories\OrderRepositories;
use think\Request;

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
        $payPrice = $request->post('totalPrice');
        return $this->orderRepositories->payment($orderSn,$payPrice);
    }


}