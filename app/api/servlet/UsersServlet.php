<?php

namespace app\api\servlet;

use app\common\model\UsersModel;
use app\lib\exception\ParameterException;
use think\Model;

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

    /**
     * createNewUser
     * @param array $userProfile
     * @return UsersModel
     */
    public function createNewUser(array $userProfile): UsersModel
    {
        return $this->usersModel::create($userProfile, ["userName", "email", "userAvatar", "balance", "password", "isStore"]);
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

    /**
     * getUserAddressList
     * @return mixed
     */
    public function getUserAddressList()
    {
        return app()->get("userProfile")->shipAddress()->field(["id", "receiver", "mobile", "address", "postCode", "isDefault", "createdAt"])->order("isDefault", "desc")->paginate();
    }

    /**
     * createNewUserAddress
     * @param array $userAddress
     * @return bool
     */
    public function createNewUserAddress(array $userAddress)
    {
        /**
         * @var UsersModel $userProfile
         */
        $userProfile = app()->get("userProfile");
        $createAddress = $userProfile->shipAddress()->save($userAddress);

        if ($userAddress["isDefault"] == 1) {
            $this->editDefaultProperties($createAddress->getAttr("id"));
        }

        return true;
    }

    // 更新其余的地址默认的属性
    // Update the default properties of the rest of the addresses
    protected function editDefaultProperties(int $addressID)
    {
        return app()->get("userProfile")->shipAddress()->whereNotIn("id", $addressID)->update(["isDefault" => 0]);
    }

    /**
     * editUserAddress
     * @param int $addressID
     * @param array $userAddress
     * @return mixed
     */
    public function editUserAddress(int $addressID, array $userAddress)
    {
        $updateResult = app()->get("userProfile")->shipAddress()->where("id", $addressID)->update($userAddress);

        if ($updateResult && $userAddress["isDefault"] == 1) {
            $this->editDefaultProperties($addressID);
        }

        return true;
    }

    /**
     * deleteUserAddress
     * @param int $addressID
     * @return mixed
     */
    public function deleteUserAddress(int $addressID)
    {
        return app()->get("userProfile")->shipAddress()->where("id", $addressID)->delete();
    }

    /**
     * getUserShoppingCartList
     * @return mixed
     */
    public function getUserShoppingCartList()
    {
        return app()->get("userProfile")->shoppingCart()->order("createdAt", "desc")->select();
    }

    /**
     * getUserShoppingCount
     * @return mixed
     */
    public function getUserShoppingCount()
    {
        return app()->get("userProfile")?->shoppingCart()->count();
    }

    /**
     * @param int $addressID
     * @return mixed
     */
    public function isValidAddress(int $addressID)
    {
        return app()->get("userProfile")->shipAddress()->where("id", $addressID)->find();
    }

}