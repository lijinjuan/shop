<?php

namespace app\store\servlet;

use app\store\servlet\contract\ServletFactoryInterface;

/**
 * \app\store\servlet\ServletFactory
 */
class ServletFactory implements ServletFactoryInterface
{

    /**
     * userServ
     * @return \app\store\servlet\UsersServlet
     */
    public function userServ(): UsersServlet
    {
        return invoke(UsersServlet::class);
    }

    /**
     * storeServ
     * @return \app\store\servlet\StoreServlet
     */
    public function storeServ(): StoreServlet
    {
        return invoke(StoreServlet::class);
    }

    /**
     * orderServ
     * @return \app\store\servlet\OrdersServlet
     */
    public function orderServ(): OrdersServlet
    {
        return invoke(OrdersServlet::class);
    }

    /**
     * goodsServ
     * @return \app\store\servlet\GoodsServlet
     */
    public function goodsServ(): GoodsServlet
    {
        return invoke(GoodsServlet::class);
    }
}