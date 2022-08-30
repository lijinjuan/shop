<?php

namespace app\api\repositories;

use app\common\model\UsersModel;
use app\lib\exception\ParameterException;
use thans\jwt\facade\JWTAuth;
use think\facade\Cache;
use think\Model;

/**
 * \app\api\repositories\UsersRepositories
 */
class UsersRepositories extends AbstractRepositories
{
    /**
     * user2launch
     * @param array $userProfile
     * @return \think\response\Json
     */
    public function user2launch(array $userProfile)
    {
        $userModel = $this->servletFactory->userServ()->getUserProfileByFields(["email" => trim($userProfile["email"])]);

        if (!$this->isEqualByPassword($userModel->getAttr("password"), trim($userProfile["password"]))) {
            throw new ParameterException(["errMessage" => "username or password is incorrect"]);
        }

        $userModel->loginNum = (int)$userModel->loginNum + 1;
        $userModel->lastIP = request()->ip();
        $userModel->lastLoginTime = date("Y-m-d H:i:s");
        $userModel->save();

        return $this->getLaunch2UserProfile($userModel);
    }

    /**
     * registerNewUser
     * @param array $userProfile
     * @return \think\response\Json
     */
    public function registerNewUser(array $userProfile)
    {
        $userModel = $this->servletFactory->userServ()->createNewUser($userProfile);
        return $this->getLaunch2UserProfile($userModel);
    }

    /**
     * getLaunch2UserProfile
     * @param \app\common\model\UsersModel $userModel
     * @return \think\response\Json
     */
    protected function getLaunch2UserProfile(UsersModel $userModel)
    {
        $accessToken = JWTAuth::builder(["userID" => (int)$userModel->getAttr("id")]);
        $creditScore = ($userModel->isStore != 0) ? $userModel->store->creditScore : 0;
        return renderResponse($this->getUserProfileByUserID($userModel, $accessToken, $creditScore));
    }

    /**
     * getUserProfileByUserID
     * @param \think\Model $userModel
     * @param string $accessToken
     * @param int $creditScore
     * @return mixed
     */
    protected function getUserProfileByUserID(Model $userModel, string $accessToken, int $creditScore)
    {
        return new class($userModel->hidden(["password", "createdAt", "updatedAt", "deletedAt", "store"]), $accessToken, $creditScore) {
            public function __construct(public Model $userProfile, public string $accessToken, public int $creditScore)
            {
            }
        };
    }

    /**
     * isEqualByPassword
     * @param string $origin
     * @param string $input
     * @return bool
     */
    protected function isEqualByPassword(string $origin, string $input): bool
    {
        return password_verify($input, $origin);
    }

    /**
     * alterUserPassword
     * @param string $loginPassword
     * @param string $payPassword
     * @param string $emailCode
     */
    public function alterUserPassword(string $loginPassword, string $payPassword, string $email, int $verifyCode)
    {
        if ($email == "")
            throw new ParameterException(["errMessage" => "请输入正确的邮箱..."]);

        if (!$verifyCode)
            throw new ParameterException(["errMessage" => "请输入正确的邮箱验证码..."]);

        $originVerifyCode = Cache::get($email);

        if ($originVerifyCode != $verifyCode)
            throw new ParameterException(["errMessage" => "验证码过期或者不正确..."]);

        $this->servletFactory->userServ()->alterUserPassword($loginPassword, $payPassword);
        return renderResponse();
    }

    /**
     * getUserBaseInfo
     * @return \think\response\Json
     */
    public function getUserBaseInfo()
    {
        $userModel = app()->get("userProfile");
        $creditScore = ($userModel->isStore != 0) ? $userModel->store->creditScore : 0;
        $userModel->creditScore = (int)$creditScore;
        return renderResponse($userModel->hidden(["password", "createdAt", "updatedAt", "deletedAt"]));
    }

    /**
     * @return \think\response\Json
     */
    public function getStoreInfo()
    {
        $data = [];
        $userModel = app()->get("userProfile")->store;
        if ($userModel) {
            $data = ['id' => $userModel->id, 'storeLogo' => $userModel->storeLogo, 'inviteCode' => $userModel->inviteCode];
        }
        return renderResponse($data);
    }
}