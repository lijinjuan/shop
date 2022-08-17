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


    public function placeOrder(Request $request)
    {
        $addressID = $request->post('addressID');
        $storeID = $request->post('storeID', 0);
        $goodsInfo = $request->post('goodsInfo', []);
        return $this->orderRepositories->placeOrder($addressID, $goodsInfo, $storeID);

    }

    public function payment()
    {

    }


}