<?php

namespace app\lib\exception;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * @var int
     */
    protected int $code;

    /**
     * @var int
     */
    protected int $errCode;

    /**
     * @var string
     */
    protected string $errMessage;

    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制
        if ($e instanceof BaseException) {
            $this->code = $e->getCode();
            $this->errCode = $e->getErrCode();
            $this->errMessage = $e->getErrMessage();
        } else {
            // 如果是服务器未处理的异常，将http状态码设置为500，并记录日志
            if ($this->app->isDebug()) {
                // 调试状态下需要显示TP默认的异常页面，因为TP的默认页面
                // 很容易看出问题
                return parent::render($request, $e);
            }

            $this->code = 500;
            $this->errCode = 1000999;
            $this->errMessage = 'sorry，we make a mistake. (^o^)Y';
            $this->report($e);
        }
        return json([
            'errCode' => $this->errCode,
            'errMessage' => $this->errMessage,
            'requestUrl' => $request->url()
        ], $this->code);
    }
}
