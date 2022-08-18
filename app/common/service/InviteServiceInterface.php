<?php

namespace app\common\service;

/**
 * \app\common\service\InviteServiceInterface
 */
interface InviteServiceInterface
{
    /**
     * enCode
     * @param int $user_id
     * @return string
     */
    public function enCode(int $user_id): string ;

    /**
     * deCode
     * @param $code
     * @return int
     */
    public function deCode($code): int;
}