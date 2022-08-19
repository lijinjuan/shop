<?php

namespace app\admin\servlet\contract;


use app\admin\servlet\AdminsServlet;

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
}