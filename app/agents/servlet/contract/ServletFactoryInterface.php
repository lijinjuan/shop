<?php

namespace app\agents\servlet\contract;


use app\agents\servlet\AgentsServlet;

/**
 * \app\agents\servlet\contract\ServletFactoryInterface
 */
interface ServletFactoryInterface
{

    /**
     * agentsServ
     * @return \app\agents\servlet\AgentsServlet
     */
    public function agentsServ(): AgentsServlet;
}