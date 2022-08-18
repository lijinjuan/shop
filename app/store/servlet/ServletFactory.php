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
}