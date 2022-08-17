<?php

namespace app\api\repositories;

/**
 * \app\api\repositories\UserAddressRepositories
 */
class UserAddressRepositories extends AbstractRepositories
{

    /**
     * getUserAddressList
     * @return \think\response\Json
     */
    public function getUserAddressList()
    {
        $paginateList = $this->servletFactory->userServ()->getUserAddressList();
        return renderPaginateResponse($paginateList);
    }

    /**
     * createUserAddress
     * @param array $userAddress
     * @return \think\response\Json
     */
    public function createUserAddress(array $userAddress)
    {
        $this->servletFactory->userServ()->createNewUserAddress($userAddress);
        return renderResponse();
    }

    /**
     * editUserAddress
     * @param int $addressID
     * @param array $userAddress
     * @return \think\response\Json
     */
    public function editUserAddress(int $addressID, array $userAddress)
    {
        $this->servletFactory->userServ()->editUserAddress($addressID, $userAddress);
        return renderResponse();
    }

    /**
     * deleteUserAddress
     * @param int $addressID
     * @return \think\response\Json
     */
    public function deleteUserAddress(int $addressID)
    {
        $this->servletFactory->userServ()->deleteUserAddress($addressID);
        return renderResponse();
    }

    /**
     * getUserAddressByAddressID
     * @param int $addressID
     * @return \think\response\Json
     */
    public function getUserAddressByAddressID(int $addressID)
    {
        $address = $this->servletFactory->userServ()->getUserAddressByAddressID($addressID);
        return renderResponse($address);
    }

    /**
     * setUserAddressByDefault
     * @param int $addressID
     * @param int $isDefault
     * @return \think\response\Json
     */
    public function setUserAddressByDefault(int $addressID, int $isDefault)
    {
        $this->servletFactory->userServ()->setUserAddressByDefault($addressID, $isDefault);
        return renderResponse();
    }
}