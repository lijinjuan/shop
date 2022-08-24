<?php

namespace app\admin\servlet;

use app\admin\servlet\contract\ServletFactoryInterface;

/**
 * \app\admin\servlet\ServletFactory
 */
class ServletFactory implements ServletFactoryInterface
{

    /**
     * adminServ
     * @return \app\admin\servlet\AdminsServlet
     */
    public function adminServ(): AdminsServlet
    {
        return invoke(AdminsServlet::class);
    }

    /**
     * goodsServ
     * @return \app\admin\servlet\GoodsServlet
     */
    public function goodsServ(): GoodsServlet
    {
        return invoke(GoodsServlet::class);
    }

    /**
     * categoryServ
     * @return \app\admin\servlet\CategoryServlet
     */
    public function categoryServ(): CategoryServlet
    {
        return invoke(CategoryServlet::class);
    }

    /**
     * @return RefundConfigServlet
     */
    public function refundConfigServ(): RefundConfigServlet
    {
        return invoke(RefundConfigServlet::class);
    }

    /**
     * @return AgentsServlet
     */
    public function agentServ(): AgentsServlet
    {
        return invoke(AgentsServlet::class);
    }

    /**
     * @return UsersServlet
     */
    public function userServ(): UsersServlet
    {
        return invoke(UsersServlet::class);
    }

    /**
     * @return StoreServlet
     */
    public function storeServ(): StoreServlet
    {
        return invoke(StoreServlet::class);
    }

    /**
     * @return StoreAccountServlet
     */
    public function storeAccountServ(): StoreAccountServlet
    {
        return invoke(StoreAccountServlet::class);
    }

    /**
     * @return RechargeServet
     */
    public function rechargeServ(): RechargeServet
    {
        return invoke(RechargeServet::class);
    }

    /**
     * @return WithdrawalServlet
     */
    public function withdrawalServ(): WithdrawalServlet
    {
        return invoke(WithdrawalServlet::class);
    }

    /**
     * @return BannerServlet
     */
    public function bannerServ(): BannerServlet
    {
        return invoke(BannerServlet::class);
    }

    /**
     * @return CommissionServlet
     */
    public function commissionServ(): CommissionServlet
    {
        return invoke(CommissionServlet::class);
    }

    /**
     * @return RechargeConfigServlet
     */
    public function rechargeConfigServ(): RechargeConfigServlet
    {
        return invoke(RechargeConfigServlet::class);
    }

    /**
     * brandsServ
     * @return \app\admin\servlet\BrandsServlet
     */
    public function brandsServ(): BrandsServlet
    {
        return invoke(BrandsServlet::class);
    }

    /**
     * imageServ
     * @return \app\admin\servlet\ImagesServlet
     */
    public function imageServ(): ImagesServlet
    {
        return invoke(ImagesServlet::class);
    }

    /**
     * @return BannerItemsServlet
     */
    public function bannerItemServ(): BannerItemsServlet
    {
        return invoke(BannerItemsServlet::class);
    }

    /**
     * goodsSkuServ
     * @return \app\admin\servlet\GoodsSkuServlet
     */
    public function goodsSkuServ(): GoodsSkuServlet
    {
        return invoke(GoodsSkuServlet::class);
    }

    /**
     * orderServ
     * @return \app\admin\servlet\OrderServlet
     */
    public function orderServ(): OrderServlet
    {
        return invoke(OrderServlet::class);
    }

    /**
     * adminAccountServ
     * @return \app\admin\servlet\AdminAccountServlet
     */
    public function adminAccountServ(): AdminAccountServlet
    {
        return invoke(AdminAccountServlet::class);
    }

    /**
     * refundServ
     * @return \app\admin\servlet\RefundServlet
     */
    public function refundServ(): RefundServlet
    {
        return invoke(RefundServlet::class);
    }

    /**
     * orderDetailServ
     * @return \app\admin\servlet\OrderDetailServlet
     */
    public function orderDetailServ(): OrderDetailServlet
    {
        return invoke(OrderDetailServlet::class);
    }

    /**
     * @return HelpServlet
     */
    public function helpServ(): HelpServlet
    {
        return invoke(HelpServlet::class);
    }

    /**
     * adminBalanceServ
     * @return \app\admin\servlet\AdminBalanceServlet
     */
    public function adminBalanceServ(): AdminBalanceServlet
    {
        return invoke(AdminBalanceServlet::class);
    }

    /**
     * @return MessageServlet
     */
    public function messageServ(): MessageServlet
    {
       return invoke(MessageServlet::class);
    }
}