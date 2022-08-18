<?php

namespace app\common\service;

/**
 * \app\common\service\InviteServiceInterface
 */
interface InviteServiceInterface
{

    /**
     * agentInviteCode
     * @return string
     */
    public function agentInviteCode(): string;

    /**
     * storeInviteCode
     * @return string
     */
    public function storeInviteCode(): string;

}