<?php

namespace app\common\service;

/**
 * \app\common\service\InviteCodeService
 */
class InviteCodeService implements InviteServiceInterface
{

    /**
     * enCode
     * @return string
     */
    protected function enCode(int $length = 5): string
    {
        return substr(base_convert(md5(uniqid(md5(microtime(true)), true)), 16, 10), 0, $length);
    }

    /**
     * agentInviteCode
     * @return string
     */
    public function agentInviteCode(): string
    {
        return "A" . $this->enCode(6);
    }

    /**
     * storeInviteCode
     * @return string
     */
    public function storeInviteCode(): string
    {
        return "M" . $this->enCode(6);
    }
}