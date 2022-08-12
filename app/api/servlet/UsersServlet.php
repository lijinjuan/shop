<?php

namespace app\api\servlet;

use app\common\model\UsersModel;
use app\lib\exception\ParameterException;
use think\Model;

/**
 * \app\api\servlet\UsersServlet
 */
class UsersServlet
{
    /**
     * @var \app\common\model\UsersModel
     */
    protected UsersModel $usersModel;

    /**
     * @param \app\common\model\UsersModel $usersModel
     */
    public function __construct(UsersModel $usersModel)
    {
        $this->usersModel = $usersModel;
    }

    /**
     * createNewUser
     * @param array $userProfile
     * @return UsersModel
     */
    public function createNewUser(array $userProfile): UsersModel
    {
        return $this->usersModel::create($userProfile, ["userName", "email", "userAvatar", "balance", "password", "isStore"]);
    }

    /**
     * getUserProfileByUserEmail
     * @param string $email
     * @return UsersModel
     */
    public function getUserProfileByUserEmail(string $email, bool $passable = true): UsersModel
    {
        $user = $this->usersModel->where("email", trim($email))->find();

        if (is_null($user) && $passable) {
            throw new ParameterException(["errMessage" => "user does not exist"]);
        }

        return $user;
    }

}