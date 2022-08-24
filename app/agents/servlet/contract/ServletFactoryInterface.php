<?php

namespace app\agents\servlet\contract;


use app\agents\servlet\AgentsServlet;
use app\agents\servlet\MessageServlet;
use app\agents\servlet\OrderServlet;
use app\agents\servlet\StoreAccountServlet;
use app\agents\servlet\StoreServlet;
use app\agents\servlet\UsersRechargeServlet;
use app\agents\servlet\UsersWithdrawalServlet;

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

    /**
     * @return StoreServlet
     */
    public function storeServ(): StoreServlet;

    /**
     * @return StoreAccountServlet
     */
    public function storeAmountServ():StoreAccountServlet;

    /**
     * @return OrderServlet
     */
    public function orderServ():OrderServlet;

    /**
     * @return UsersRechargeServlet
     */
    public function rechargeServ():UsersRechargeServlet;

    /**
     * @return UsersWithdrawalServlet
     */
    public function withdrawalServ():UsersWithdrawalServlet;

    /**
     * @return MessageServlet
     */
    public function messageServ():MessageServlet;
}