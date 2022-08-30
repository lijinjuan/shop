<?php

namespace app\api\controller\v1;


use app\api\repositories\UsersRepositories;
use app\api\validate\UsersValidate;
use app\lib\exception\ParameterException;
use think\facade\Cache;
use think\Request;
use think\Response;

/**
 * \app\api\controller\v1\EntryController
 */
class EntryController
{
    /**
     * @var \app\api\repositories\UsersRepositories
     */
    protected UsersRepositories $usersRepositories;

    /**
     * @param \app\api\repositories\UsersRepositories $usersRepositories
     */
    public function __construct(UsersRepositories $usersRepositories)
    {
        $this->usersRepositories = $usersRepositories;
    }

    /**
     * userLaunch
     * @return \think\Response
     */
    public function userLaunch(Request $request): Response
    {
        (new UsersValidate())->goCheck();
        $userProfile = $request->only(["email", "password"]);
        return $this->usersRepositories->user2launch($userProfile);
    }

    /**
     * registerNewUser
     * @param \think\Request $request
     * @return mixed
     */
    public function registerNewUser(Request $request)
    {
        (new UsersValidate())->goCheck();
        $userProfile = $request->only(["email", "password", "payPassword", "verifyCode"]);

        if ($userProfile["email"] == "")
            throw new ParameterException(["errMessage" => "请输入正确的邮箱..."]);

        if (!$userProfile["verifyCode"])
            throw new ParameterException(["errMessage" => "请输入正确的邮箱验证码..."]);

        $originVerifyCode = Cache::get($userProfile["email"] . "/" . "login");

        if ($originVerifyCode != $userProfile["verifyCode"])
            throw new ParameterException(["errMessage" => "验证码过期或者不正确..."]);

        try {
            return $this->usersRepositories->registerNewUser($userProfile);
        } catch (\Throwable) {
            throw new ParameterException(["errMessage" => "注册失败，请稍后重试..."]);
        }
    }

    /**
     * alterUserPassword
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function alterUserPassword(Request $request)
    {
        $loginPassword = $request->param("inputPassword");
        $payPassword = $request->param("payPassword");
        $toEmail = $request->param("email", "");
        $verifyCode = (int)$request->param("verifyCode", 0);
        return $this->usersRepositories->alterUserPassword($loginPassword, $payPassword, $toEmail, $verifyCode);
    }

    /**
     * getUserBaseInfo
     * @return \think\response\Json
     */
    public function getUserBaseInfo()
    {
        return $this->usersRepositories->getUserBaseInfo();
    }

    /**
     * editUserInfo
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function editUserInfo(Request $request)
    {
        $userName = $request->param("userName", "");
        $userAvatar = $request->param("userAvatar", "");
        /**
         * @var $userModel \app\common\model\UsersModel
         */
        $userModel = app()->get("userProfile");
        $userModel->userName = $userName;
        $userModel->userAvatar = $userAvatar;
        $userModel->save();

        return renderResponse();
    }

    /**
     * @return \think\response\Json
     */
    public function getInviteCode()
    {
        return $this->usersRepositories->getStoreInfo();
    }
}