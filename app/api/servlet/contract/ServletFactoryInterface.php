<?php

namespace app\api\servlet\contract;

use app\api\servlet\BannersServlet;
use app\api\servlet\BrandsServlet;
use app\api\servlet\CategoryServlet;
use app\api\servlet\CommissionConfigServlet;
use app\api\servlet\GoodsServlet;
use app\api\servlet\GoodsSkuServlet;
use app\api\servlet\OrderDetailServlet;
use app\api\servlet\OrderServlet;
use app\api\servlet\RechargeConfigServlet;
use app\api\servlet\RechargeServlet;
use app\api\servlet\RefundConfigServlet;
use app\api\servlet\RefundServlet;
use app\api\servlet\ShopServlet;
use app\api\servlet\UserAddressServlet;
use app\api\servlet\UsersAmountServlet;
use app\api\servlet\UsersServlet;
use app\api\servlet\UsersShoppingCartServlet;
use app\api\servlet\WithdrawalServlet;
use app\common\model\UsersAmountModel;

/**
 * \app\api\servlet\contract\ServletFactoryInterface
 */
interface ServletFactoryInterface
{

    /**
     * userServ
     * @return \app\api\servlet\UsersServlet
     */
    public function userServ(): UsersServlet;

    /**
     * bannerServ
     * @return \app\api\servlet\BannersServlet
     */
    public function bannerServ(): BannersServlet;

    /**
     * shopServ
     * @return \app\api\servlet\ShopServlet
     */
    public function shopServ(): ShopServlet;

    /**
     * brandsServ
     * @return \app\api\servlet\BrandsServlet
     */
    public function brandsServ(): BrandsServlet;

    /**
     * usersShoppingCartServ
     * @return UsersShoppingCartServlet
     */
    public function usersShoppingCartServ(): UsersShoppingCartServlet;

    /**
     * goodsServ
     * @return GoodsServlet
     */
    public function goodsServ(): GoodsServlet;

    /**
     * categoryServ
     * @return \app\api\servlet\CategoryServlet
     */
    public function categoryServ(): CategoryServlet;

    /**
     * OrderServ
     * @return OrderServlet
     */
    public function orderServ(): OrderServlet;

    /**
     * GoodsSkuServ
     * @return GoodsSkuServlet
     */
    public function goodsSkuServ(): GoodsSkuServlet;

    /**
     * @return OrderDetailServlet
     */
    public function orderDetailServ(): OrderDetailServlet;

    /**
     * userAddressServ
     * @return \app\api\servlet\UserAddressServlet
     */
    public function userAddressServ(): UserAddressServlet;

    /**
     * commissionServ
     * @return \app\api\servlet\CommissionConfigServlet
     */
    public function commissionServ(): CommissionConfigServlet;

    /**
     * @return RefundServlet
     */
    public function refundServ():RefundServlet;

    /**
     * @return RechargeConfigServlet
     */
    public function rechargeConfigServ():RechargeConfigServlet;

    /**
     * @return RechargeServlet
     */
    public function rechargeServ():RechargeServlet;

    /**
     * @return WithdrawalServlet
     */
    public function withdrawalServ():WithdrawalServlet;

    /**
     * @return UsersAmountServlet
     */
    public function userAmountServ():UsersAmountServlet;

    /**
     * @return RefundConfigServlet
     */
    public function refundConfigServ():RefundConfigServlet;


}