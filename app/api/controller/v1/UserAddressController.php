<?php

namespace app\api\controller\v1;

use app\api\repositories\UserAddressRepositories;
use think\Request;

/**
 * \app\api\controller\v1\UserAddressController
 */
class UserAddressController
{
    /**
     * @var \app\api\repositories\UserAddressRepositories
     */
    protected UserAddressRepositories $userAddressRepositories;

    /**
     * @param \app\api\repositories\UserAddressRepositories $userAddressRepositories
     */
    public function __construct(UserAddressRepositories $userAddressRepositories)
    {
        $this->userAddressRepositories = $userAddressRepositories;
    }

    public function getUserAddressListByToken()
    {
        return $this->userAddressRepositories->getUserAddressList();
    }

    public function createUserAddress(Request $request)
    {

    }

    public function editUserAddress(Request $request)
    {

    }

    public function deleteUserAddress(int $addressID)
    {

    }


}