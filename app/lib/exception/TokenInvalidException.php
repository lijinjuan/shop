<?php

namespace app\lib\exception;

/**
 * \app\lib\exception\TokenInvalidException
 */
class TokenInvalidException extends BaseException
{
    /**
     * @var int
     */
    protected $code = 401;

    /**
     * @var int
     */
    protected int $errCode = 1000003;

    /**
     * @var string
     */
    protected string $errMessage = "token has expired or invalid token";
}