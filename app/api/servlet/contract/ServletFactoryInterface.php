<?php

namespace app\api\servlet\contract;

use app\api\servlet\BannersServlet;
use app\api\servlet\UsersServlet;

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
}