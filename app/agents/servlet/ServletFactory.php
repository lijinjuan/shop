<?php

namespace app\agents\servlet;

use app\agents\servlet\contract\ServletFactoryInterface;

/**
 * \app\agents\servlet\ServletFactory
 */
class ServletFactory implements ServletFactoryInterface
{
    /**
     * agentsServ
     * @return \app\agents\servlet\AgentsServlet
     */
    public function agentsServ(): AgentsServlet
    {
        return invoke(AgentsServlet::class);
    }
}