<?php

namespace app\admin\repositories;

use app\lib\exception\ParameterException;
use thans\jwt\facade\JWTAuth;

/**
 * \app\admin\repositories\AdminsRepositories
 */
class AdminsRepositories extends AbstractRepositories
{

    /**
     * userLaunch2Admin
     * @param array $adminProfile
     */
    public function userLaunch2Admin(array $adminProfile)
    {
        $AdminModel = $this->servletFactory->adminServ()->getAdminProfileByFields(["email" => trim($adminProfile["email"])]);
        if (!$this->isEqualByPassword($AdminModel->getAttr("password"), trim($adminProfile["password"]))) {
            throw new ParameterException(["errMessage" => "用户名或者密码错误..."]);
        }
        $accessToken = JWTAuth::builder(["adminID" => $AdminModel->id]);
        return renderResponse(compact("accessToken"));
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