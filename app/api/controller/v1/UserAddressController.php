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

    /**
     * getUserAddressListByToken
     * @return \think\response\Json
     */
    public function getUserAddressListByToken()
    {
        return $this->userAddressRepositories->getUserAddressList();
    }

    /**
     * getUserAddressByAddressID
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function getUserAddressByAddressID(int $addressID)
    {
        return $this->userAddressRepositories->getUserAddressByAddressID($addressID);
    }

    /**
     * setUserAddressByDefault
     * @param int $addressID
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function setUserAddressByDefault(int $addressID, Request $request)
    {
        $isDefault = (int)$request->param("isDefault", 0);
        return $this->userAddressRepositories->setUserAddressByDefault($addressID, $isDefault);
    }

    /**
     * createUserAddress
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function createUserAddress(Request $request)
    {
        $userAddress = $request->only(["receiver", "mobile", "address", "postCode", "isDefault"]);
        return $this->userAddressRepositories->createUserAddress($userAddress);
    }

    /**
     * editUserAddress
     * @param int $addressID
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function editUserAddress(int $addressID, Request $request)
    {
        $userAddress = $request->only(["receiver", "mobile", "address", "postCode", "isDefault"]);
        return $this->userAddressRepositories->editUserAddress($addressID, $userAddress);
    }

    /**
     * deleteUserAddress
     * @param int $addressID
     */
    public function deleteUserAddress(int $addressID)
    {
        return $this->userAddressRepositories->deleteUserAddress($addressID);
    }


}