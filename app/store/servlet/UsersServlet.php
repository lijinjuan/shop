<?php

namespace app\store\servlet;

use app\common\model\UsersModel;
use app\lib\exception\ParameterException;

/**
 * \app\store\servlet\UsersServlet
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
     * getUserProfileByFields
     * @param array $whereFields
     * @param bool $passable
     * @return \app\common\model\UsersModel
     */
    public function getUserProfileByFields(array $whereFields, bool $passable = true): UsersModel
    {
        $user = $this->usersModel->where($whereFields)->find();

        if (is_null($user) && $passable) {
            throw new ParameterException(["errMessage" => "user does not exist"]);
        }

        return $user;
    }
}