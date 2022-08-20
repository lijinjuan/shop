<?php

namespace app\store\controller\v1;

use app\lib\exception\ParameterException;
use app\store\repositories\StoreRepositories;
use think\captcha\facade\Captcha;
use think\Request;

/**
 * \app\store\controller\v1\EntryController
 */
class EntryController
{
    /**
     * @var \app\store\repositories\StoreRepositories
     */
    protected StoreRepositories $storeRepositories;

    /**
     * @param \app\store\repositories\StoreRepositories $storeRepositories
     */
    public function __construct(StoreRepositories $storeRepositories)
    {
        $this->storeRepositories = $storeRepositories;
    }

    /**
     * createCaptcha
     * @return \think\Response
     */
    public function createCaptcha()
    {
        return Captcha::create();
    }

    /**
     * storeLaunch
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function storeLaunch(Request $request)
    {
        $verifyCode = trim($request->param("verifyCode"));

//        if (!captcha_check($verifyCode)) {
//            throw new ParameterException(["errMessage" => "验证码不正确..."]);
//        }

        $userProfile = $request->only(["email", "password"]);

        return $this->storeRepositories->store2Launch($userProfile);
    }

    /**
     * alterUserPassword
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function alterUserPassword(Request $request)
    {
        $loginPassword = $request->param("originPassword");
        $inputPassword = $request->param("inputPassword");
        $rePassword = $request->param("rePassword");

        if ($inputPassword != $rePassword) {
            throw new ParameterException(["errMessage" => "前后两次输入的密码不一致..."]);
        }

        return $this->storeRepositories->alterUserPassword($loginPassword, $inputPassword);
    }
}