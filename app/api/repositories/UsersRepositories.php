<?php

namespace app\api\repositories;

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
        return renderResponse($userProfile);
    }
}