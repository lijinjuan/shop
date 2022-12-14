<?php

namespace app\admin\servlet\contract;


use app\admin\servlet\AboutUsServlet;
use app\admin\servlet\AdminAccountServlet;
use app\admin\servlet\AdminBalanceServlet;
use app\admin\servlet\AdminsServlet;
use app\admin\servlet\AgentsServlet;
use app\admin\servlet\BannerItemsServlet;
use app\admin\servlet\BannerServlet;
use app\admin\servlet\BrandsServlet;
use app\admin\servlet\CategoryServlet;
use app\admin\servlet\CommissionServlet;
use app\admin\servlet\GoodsServlet;
use app\admin\servlet\GoodsSkuServlet;
use app\admin\servlet\HelpServlet;
use app\admin\servlet\ImagesServlet;
use app\admin\servlet\MessageServlet;
use app\admin\servlet\OrderDetailServlet;
use app\admin\servlet\OrderServlet;
use app\admin\servlet\RechargeConfigServlet;
use app\admin\servlet\RechargeServet;
use app\admin\servlet\RefundConfigServlet;
use app\admin\servlet\RefundServlet;
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
    public function agentServ(): AgentsServlet;

    /**
     * @return UsersServlet
     */
    public function userServ(): UsersServlet;

    /**
     * @return StoreServlet
     */
    public function storeServ(): StoreServlet;

    /**
     * @return StoreAccountServlet
     */
    public function storeAccountServ(): StoreAccountServlet;

    /**
     * @return RechargeServet
     */
    public function rechargeServ(): RechargeServet;

    /**
     * @return WithdrawalServlet
     */
    public function withdrawalServ(): WithdrawalServlet;

    /**
     * @return CommissionServlet
     */
    public function commissionServ(): CommissionServlet;

    /**
     * @return BannerServlet
     */
    public function bannerServ(): BannerServlet;

    /**
     * @return RechargeConfigServlet
     */
    public function rechargeConfigServ(): RechargeConfigServlet;

    /**
     * brandsServ
     * @return \app\admin\servlet\BrandsServlet
     */
    public function brandsServ(): BrandsServlet;

    /**
     * imageServ
     * @return \app\admin\servlet\ImagesServlet
     */
    public function imageServ(): ImagesServlet;

    /**
     * @return BannerItemsServlet
     */
    public function bannerItemServ(): BannerItemsServlet;

    /**
     * goodsSkuServ
     * @return \app\admin\servlet\GoodsSkuServlet
     */
    public function goodsSkuServ(): GoodsSkuServlet;

    /**
     * orderServ
     * @return \app\admin\servlet\OrderServlet
     */
    public function orderServ(): OrderServlet;

    /**
     * adminAccountServ
     * @return \app\admin\servlet\AdminAccountServlet
     */
    public function adminAccountServ(): AdminAccountServlet;

    /**
     * refundServ
     * @return \app\admin\servlet\RefundServlet
     */
    public function refundServ(): RefundServlet;

    /**
     * orderDetailServ
     * @return \app\admin\servlet\OrderDetailServlet
     */
    public function orderDetailServ(): OrderDetailServlet;

    /**
     * @return HelpServlet
     */
    public function helpServ(): HelpServlet;

    /**
     * adminBalanceServ
     * @return \app\admin\servlet\AdminBalanceServlet
     */
    public function adminBalanceServ(): AdminBalanceServlet;

    /**
     * @return MessageServlet
     */
    public function messageServ():MessageServlet;



}