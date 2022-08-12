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
         * @return true
         * @throws ParameterException
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

    /**
     * isLeastAlphaDash
     * @param mixed $value
     * @param mixed|string $rule
     * @param array $data
     * @param string $field
     * @return bool|string
     */
    protected function isLeastAlphaDash(mixed $value, mixed $rule = '', array $data = [], string $field = "")
    {
        if (preg_match('/(?=.*[a-z])(?=.*[0-9])[A-Za-z0-9]/', $value)) {
            return true;
        } else {
            return $field . "至少包含一个数字或者小写字母";
        }
    }
}