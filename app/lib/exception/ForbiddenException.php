<?php

namespace app\lib\exception;

/**
 * \app\lib\exception\ForbiddenException
 */
class ForbiddenException extends BaseException
{
    /**
     * @var int
     */
    protected $code = 403;

    /**
     * @var int
     */
    protected int $errCode = 1000002;

    /**
     * @var string
     */
    protected string $errMessage = "insufficient permissions";
}