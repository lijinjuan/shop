<?php

namespace app\admin\servlet;

use app\admin\servlet\contract\ServletFactoryInterface;

/**
 * \app\admin\servlet\ServletFactory
 */
class ServletFactory implements ServletFactoryInterface
{

    /**
     * adminServ
     * @return \app\admin\servlet\AdminsServlet
     */
    public function adminServ(): AdminsServlet
    {
        return invoke(AdminsServlet::class);
    }

    /**
     * goodsServ
     * @return \app\admin\servlet\GoodsServlet
     */
    public function goodsServ(): GoodsServlet
    {
        return invoke(GoodsServlet::class);
    }

    /**
     * categoryServ
     * @return \app\admin\servlet\CategoryServlet
     */
    public function categoryServ(): CategoryServlet
    {
        return invoke(CategoryServlet::class);
    }
}