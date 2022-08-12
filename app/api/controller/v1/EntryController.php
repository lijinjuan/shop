<?php

namespace app\api\controller\v1;


use app\api\repositories\UsersRepositories;
use app\api\validate\UsersValidate;
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
        $userProfile = $request->only(["email", "password"]);
        return $this->usersRepositories->registerNewUser($userProfile);
    }
}