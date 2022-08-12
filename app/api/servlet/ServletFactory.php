<?php

namespace app\api\servlet;

use app\api\servlet\contract\ServletFactoryInterface;

/**
 * \app\api\servlet\ServletFactory
 */
class ServletFactory implements ServletFactoryInterface
{

    /**
     * BannerServ
     * @return mixed
     */
    public function BannerServ(): BannersServlet
    {
        return invoke(BannersServlet::class);
    }


}