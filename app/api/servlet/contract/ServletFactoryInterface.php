<?php

namespace app\api\servlet\contract;

use app\api\servlet\BannersServlet;
use app\api\servlet\BrandsServlet;
use app\api\servlet\GoodsServlet;
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

<<<<<<< HEAD
    public function goodsServ();
=======
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
>>>>>>> 4c71783b6c952c815e11236817e7df14c300a707

}