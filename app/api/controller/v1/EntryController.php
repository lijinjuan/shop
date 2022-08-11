<?php

namespace app\api\controller\v1;


use app\api\repositories\UsersRepositories;
use app\api\validate\UsersValidate;
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
    public function userLaunch(): Response
    {
        (new UsersValidate())->goCheck();
        $userProfile = request()->only(["userEmail", "password"]);
        return $this->usersRepositories->user2launch($userProfile);
    }
}