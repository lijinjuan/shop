<?php

namespace app\admin\servlet\contract;


use app\admin\servlet\AdminsServlet;
use app\admin\servlet\CategoryServlet;
use app\admin\servlet\GoodsServlet;
use app\admin\servlet\RefundConfigServlet;

/**
 * \app\admin\servlet\contract\ServletFactoryInterface
 */
interface ServletFactoryInterface
{

    /**
     * adminServ
     * @return \app\admin\servlet\AdminsServlet
     */
    public function adminServ(): AdminsServlet;

    /**
     * goodsServ
     * @return \app\admin\servlet\GoodsServlet
     */
    public function goodsServ(): GoodsServlet;

    /**
     * categoryServ
     * @return \app\admin\servlet\CategoryServlet
     */
    public function categoryServ(): CategoryServlet;

    /**
     * @return RefundConfigServlet
     */
    public function refundConfigServ(): RefundConfigServlet;
}