<?php

namespace app\api\servlet;

use app\api\servlet\contract\ServletFactoryInterface;

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
     * @return GoodsServlet
     */
    public function GoodsServ(): GoodsServlet
    {
        return invoke(GoodsServlet::class);
    }

    /**
     * @return OrderServlet
     */
    public function OrderServ(): OrderServlet
    {
        return invoke(OrderServlet::class);
    }

    /**
     * @return GoodsSkuServlet
     */
    public function GoodsSkuServ(): GoodsSkuServlet
    {
       return invoke(GoodsSkuServlet::class);
    }


}