<?php

namespace app\admin\controller\v1;

use app\admin\repositories\AdminsRepositories;
use app\lib\exception\ParameterException;
use think\captcha\facade\Captcha;
use think\Request;

/**
 * \app\admin\controller\v1\EntryController
 */
class EntryController
{
    /**
     * @var \app\admin\repositories\AdminsRepositories
     */
    protected AdminsRepositories $adminsRepositories;

    /**
     * @param \app\admin\repositories\AdminsRepositories $adminsRepositories
     */
    public function __construct(AdminsRepositories $adminsRepositories)
    {
        $this->adminsRepositories = $adminsRepositories;
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
     * admin2Launch
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function admin2Launch(Request $request)
    {
        $verifyCode = (string)$request->param("verifyCode", "");

//        if (!captcha_check($verifyCode)) {
//            throw new ParameterException(["errMessage" => "验证码错误或者失效..."]);
//        }

        $adminProfile = $request->only(["email", "password"]);

        return $this->adminsRepositories->userLaunch2Admin($adminProfile);
    }

}