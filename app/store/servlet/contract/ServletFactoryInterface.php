<?php

namespace app\store\servlet\contract;


use app\store\servlet\AdminAccountServlet;
use app\store\servlet\AdminBalanceServlet;
use app\store\servlet\CommissionServlet;
use app\store\servlet\GoodsServlet;
use app\store\servlet\OrdersServlet;
use app\store\servlet\StoreServlet;
use app\store\servlet\UsersServlet;

/**
 * \app\store\servlet\contract\ServletFactoryInterface
 */
interface ServletFactoryInterface
{

    /**
     * storeServ
     * @return \app\store\servlet\StoreServlet
     */
    public function storeServ(): StoreServlet;

    /**
     * userServ
     * @return \app\store\servlet\UsersServlet
     */
    public function userServ(): UsersServlet;

    /**
     * orderServ
     * @return \app\store\servlet\OrdersServlet
     */
    public function orderServ(): OrdersServlet;

    /**
     * goodsServ
     * @return \app\store\servlet\GoodsServlet
     */
    public function goodsServ(): GoodsServlet;

    /**
     * commissionServ
     * @return \app\store\servlet\CommissionServlet
     */
    public function commissionServ(): CommissionServlet;

    /**
     * adminAccountServ
     * @return \app\store\servlet\AdminAccountServlet
     */
    public function adminAccountServ(): AdminAccountServlet;

    /**
     * adminBalanceServ
     * @return \app\store\servlet\AdminBalanceServlet
     */
    public function adminBalanceServ(): AdminBalanceServlet;
}