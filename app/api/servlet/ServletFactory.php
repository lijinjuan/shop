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
     * @return mixed
     */
    public function bannerServ(): BannersServlet
    {
        return invoke(BannersServlet::class);
    }


}