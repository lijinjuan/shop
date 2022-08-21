<?php

namespace app\admin\servlet\contract;


use app\admin\servlet\AdminsServlet;
use app\admin\servlet\AgentsServlet;
use app\admin\servlet\BannerServlet;
use app\admin\servlet\BrandsServlet;
use app\admin\servlet\CategoryServlet;
use app\admin\servlet\CommissionServlet;
use app\admin\servlet\GoodsServlet;
use app\admin\servlet\RechargeConfigServlet;
use app\admin\servlet\RechargeServet;
use app\admin\servlet\RefundConfigServlet;
use app\admin\servlet\StoreAccountServlet;
use app\admin\servlet\StoreServlet;
use app\admin\servlet\UsersServlet;
use app\admin\servlet\WithdrawalServlet;

/**
 * \app\admin\servlet\contract\ServletFactoryInterface
 */
interface ServletFactoryInterface
{

    /**
     * adminServ
     * @return \app\admin\servlet\AdminsServlet
     */
    public function adminServ(): AdminsServlet;

    /**
     * goodsServ
     * @return \app\admin\servlet\GoodsServlet
     */
    public function goodsServ(): GoodsServlet;

    /**
     * categoryServ
     * @return \app\admin\servlet\CategoryServlet
     */
    public function categoryServ(): CategoryServlet;

    /**
     * @return RefundConfigServlet
     */
    public function refundConfigServ(): RefundConfigServlet;

    /**
     * @return AgentsServlet
     */
    public function agentServ():AgentsServlet;

    /**
     * @return UsersServlet
     */
    public function userServ():UsersServlet;

    /**
     * @return StoreServlet
     */
    public function storeServ():StoreServlet;

    /**
     * @return StoreAccountServlet
     */
    public function storeAccountServ():StoreAccountServlet;

    /**
     * @return RechargeServet
     */
    public function rechargeServ():RechargeServet;

    /**
     * @return WithdrawalServlet
     */
    public function withdrawalServ():WithdrawalServlet;

    /**
     * @return CommissionServlet
     */
    public function commissionServ():CommissionServlet;

    /**
     * @return BannerServlet
     */
    public function bannerServ():BannerServlet;

    /**
     * @return RechargeConfigServlet
     */
    public function rechargeConfigServ():RechargeConfigServlet;

    /**
     * brandsServ
     * @return \app\admin\servlet\BrandsServlet
     */
    public function brandsServ(): BrandsServlet;
}