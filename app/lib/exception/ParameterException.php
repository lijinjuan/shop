<?php

namespace app\lib\exception;

/**
 * \app\lib\exception\ParameterException
 */
class ParameterException extends BaseException
{
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var int
     */
    protected int $errCode = 1000001;

    /**
     * @var string
     */
    protected string $errMessage = "invalid parameters";
}