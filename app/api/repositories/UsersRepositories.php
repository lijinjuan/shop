<?php

namespace app\api\repositories;


use thans\jwt\facade\JWTAuth;

/**
 * \app\api\repositories\UsersRepositories
 */
class UsersRepositories
{
    /**
     * user2launch
     * @param array $userProfile
     */
    public function user2launch(array $userProfile)
    {
        $token = JWTAuth::builder(["uid" => 1]);
        return renderResponse(compact('token'));
    }
}