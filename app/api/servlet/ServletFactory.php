<?php

namespace app\api\servlet;

use app\api\servlet\contract\ServletFactoryInterface;
use app\common\model\AdminsAccountModel;

/**
 * \app\api\servlet\ServletFactory
 */
class ServletFactory implements ServletFactoryInterface
{

    /**
     * userServ
     * @return \app\api\servlet\UsersServlet
     */
    public function userServ(): UsersServlet
    {
        return invoke(UsersServlet::class);
    }

    /**
     * bannerServ
     * @return \app\api\servlet\BannersServlet
     */
    public function bannerServ(): BannersServlet
    {
        return invoke(BannersServlet::class);
    }

    /**
     * shopServ
     * @return \app\api\servlet\ShopServlet
     */
    public function shopServ(): ShopServlet
    {
        return invoke(ShopServlet::class);
    }

    /**
     * brandsServ
     * @return \app\api\servlet\BrandsServlet
     */
    public function brandsServ(): BrandsServlet
    {
        return invoke(BrandsServlet::class);
    }

    /**
     * usersShoppingCartServ
     * @return UsersShoppingCartServlet
     */
    public function usersShoppingCartServ(): UsersShoppingCartServlet
    {
        return invoke(UsersShoppingCartServlet::class);
    }

    /**
     * GoodsServ
     * @return \app\api\servlet\GoodsServlet
     */
    public function goodsServ(): GoodsServlet
    {
        return invoke(GoodsServlet::class);
    }

    /**
     * @return OrderServlet
     */
    public function orderServ(): OrderServlet
    {
        return invoke(OrderServlet::class);
    }

    /**
     * @return GoodsSkuServlet
     */
    public function goodsSkuServ(): GoodsSkuServlet
    {
        return invoke(GoodsSkuServlet::class);
    }

    /*
     * categoryServ
     * @return \app\api\servlet\CategoryServlet
     */
    public function categoryServ(): CategoryServlet
    {
        return invoke(CategoryServlet::class);
    }

    /**
     * @return OrderDetailServlet
     */
    public function orderDetailServ(): OrderDetailServlet
    {
        return invoke(OrderDetailServlet::class);
    }

    /**
     * userAddressServ
     * @return \app\api\servlet\UserAddressServlet
     */
    public function userAddressServ(): UserAddressServlet
    {
        return invoke(UserAddressServlet::class);
    }

    /**
     * commissionServ
     * @return \app\api\servlet\CommissionConfigServlet
     */
    public function commissionServ(): CommissionConfigServlet
    {
        return invoke(CommissionConfigServlet::class);
    }

    /**
     * @return RefundServlet
     */
    public function refundServ(): RefundServlet
    {
        return invoke(RefundServlet::class);
    }

    /**
     * @return RechargeConfigServlet
     */
    public function rechargeConfigServ(): RechargeConfigServlet
    {
        return invoke(RechargeConfigServlet::class);
    }

    /**
     * @return RechargeServlet
     */
    public function rechargeServ(): RechargeServlet
    {
        return invoke(RechargeServlet::class);
    }

    /**
     * @return UsersAmountServlet
     */
    public function userAmountServ(): UsersAmountServlet
    {
        return invoke(UsersAmountServlet::class);
    }

    /**
     * @return WithdrawalServlet
     */
    public function withdrawalServ(): WithdrawalServlet
    {
        return invoke(WithdrawalServlet::class);
    }

    /**
     * @return RefundConfigServlet
     */
    public function refundConfigServ(): RefundConfigServlet
    {
        return invoke(RefundConfigServlet::class);
    }

    /**
     * agentServ
     * @return \app\api\servlet\AgentsServlet
     */
    public function agentServ(): AgentsServlet
    {
        return invoke(AgentsServlet::class);
    }

    /**
     * @return StoreAccountServlet
     */
    public function storeAccountServ(): StoreAccountServlet
    {
        return invoke(StoreAccountServlet::class);
    }

    /**
     * @return AdminAccountServlet
     */
    public function adminAccountServ(): AdminAccountServlet
    {
        return invoke(AdminAccountServlet::class);
    }

    /**
     * @return AdminBalanceServlet
     */
    public function adminBalanceServ(): AdminBalanceServlet
    {
        return invoke(AdminBalanceServlet::class);
    }

    /**
     * @return HelpServlet
     */
    public function helpServ(): HelpServlet
    {
        return invoke(HelpServlet::class);
    }

    /**
     * @return MessageServlet
     */
    public function messageServ(): MessageServlet
    {
        return invoke(MessageServlet::class);
    }

    /**
     * @return ChatMessageServlet
     */
    public function chatMessageServ(): ChatMessageServlet
    {
        return invoke(ChatMessageServlet::class);
    }
}