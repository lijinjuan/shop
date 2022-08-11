<?php

namespace app\lib\exception;

use think\Exception;

/**
 * \app\library\BaseException
 */
class BaseException extends Exception
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

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        if(!is_array($params)){
            return;
        }

        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }

        if(array_key_exists('errCode',$params)){
            $this->errCode = $params['errCode'];
        }

        if(array_key_exists('errMessage',$params)){
            $this->errMessage = $params['errMessage'];
        }
    }

    /**
     * @return int
     */
    public function getErrCode(): int
    {
        return $this->errCode;
    }

    /**
     * @return string
     */
    public function getErrMessage(): string
    {
        return $this->errMessage;
    }
}