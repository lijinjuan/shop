<?php

namespace app\api\servlet\contract;

use app\api\servlet\BannersServlet;
use app\api\servlet\BrandsServlet;
use app\api\servlet\GoodsServlet;
use app\api\servlet\GoodsSkuServlet;
use app\api\servlet\OrderServlet;
use app\api\servlet\ShopServlet;
use app\api\servlet\UsersServlet;
use app\api\servlet\UsersShoppingCartServlet;

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
    public function usersShoppingCartServ():UsersShoppingCartServlet;


    /**
     * GoodsServ
     * @return GoodsServlet
     */
    public function GoodsServ():GoodsServlet;

    /**
     * OrderServ
     * @return OrderServlet
     */
    public function OrderServ():OrderServlet;

    /**
     * GoodsSkuServ
     * @return GoodsSkuServlet
     */
    public function GoodsSkuServ():GoodsSkuServlet;


}