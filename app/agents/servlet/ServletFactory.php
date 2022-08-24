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

    /**
     * @return StoreServlet
     */
    public function storeServ(): StoreServlet
    {
        return invoke(StoreServlet::class);
    }

    /**
     * @return StoreAccountServlet
     */
    public function storeAmountServ(): StoreAccountServlet
    {
        return invoke(StoreAccountServlet::class);
    }

    /**
     * @return OrderServlet
     */
    public function orderServ(): OrderServlet
    {
        return invoke(OrderServlet::class);
    }

    /**
     * @return UsersWithdrawalServlet
     */
    public function withdrawalServ(): UsersWithdrawalServlet
    {
        return invoke(UsersWithdrawalServlet::class);
    }

    /**
     * @return UsersRechargeServlet
     */
    public function rechargeServ(): UsersRechargeServlet
    {
       return invoke(UsersRechargeServlet::class);
    }

    /**
     * @return MessageServlet
     */
    public function messageServ(): MessageServlet
    {
        return invoke(MessageServlet::class);
    }
}