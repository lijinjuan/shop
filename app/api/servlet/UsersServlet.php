<?php

namespace app\api\servlet;

use app\common\model\UsersModel;

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



}