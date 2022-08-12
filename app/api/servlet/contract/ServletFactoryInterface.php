<?php

namespace app\api\servlet\contract;

use app\api\servlet\BannersServlet;

/**
 * \app\api\servlet\contract\ServletFactoryInterface
 */
interface ServletFactoryInterface
{

    public function bannerServ(): BannersServlet;
}