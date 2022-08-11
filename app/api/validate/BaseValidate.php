<?php

namespace app\api\validate;

use app\lib\exception\ParameterException;
use think\Validate;

/**
 * \app\api\validate\BaseValidate
 */
class BaseValidate extends Validate
{

    /**
     * goCheck
     * @return bool
     */
    public function goCheck()
    {
        $params = $this->request->param();

        /**
         * 检测所有客户端发来的参数是否符合验证类规则
         * 基类定义了很多自定义验证方法
         * 这些自定义验证方法其实，也可以直接调用
         * @throws ParameterException
         * @return true
         */
        if (!$this->check($params)) {
            $exception = new ParameterException([
                    // $this->error有一个问题，并不是一定返回数组，需要判断
                    'errMessage' => is_array($this->error) ? implode(
                        ';', $this->error) : $this->error,
                ]);
            throw $exception;
        }

        return true;
    }
}