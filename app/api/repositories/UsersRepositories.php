<?php

namespace app\api\repositories;


use app\common\model\UsersModel;
use app\lib\exception\ParameterException;
use thans\jwt\facade\JWTAuth;
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
        return renderResponse($this->getUserProfileByUserID($userModel, $accessToken));
    }

    /**
     * getUserProfileByUserID
     * @param Model $user
     * @param string $accessToken
     * @return mixed
     */
    protected function getUserProfileByUserID(Model $userModel, string $accessToken)
    {
        return new class($userModel->hidden(["password", "createdAt", "updatedAt", "deletedAt"]), $accessToken) {
            public function __construct(public Model $userProfile, public string $accessToken)
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
}