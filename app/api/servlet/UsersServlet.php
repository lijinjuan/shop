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
        return $this->usersModel::create($userProfile, ["userName", "email", "payPassword", "userAvatar", "balance", "password", "isStore"]);
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
        return app()->get("userProfile")->shipAddress()->field(["id", "receiver", "mobile", "address", "postCode", "isDefault", "createdAt"])->order("isDefault", "desc")->paginate((int)request()->param("pageSize"));
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
     * @return float|int
     */
    public function getUserShoppingCount()
    {
        $cart =  app()->get("userProfile")?->shoppingCart->toArray();
        return array_sum(array_column($cart,'goodsNum'));
    }


    /**
     * @param int $addressID
     * @return mixed
     */
    public function isValidAddress(int $addressID)
    {
        return app()->get("userProfile")->shipAddress()->where("id", $addressID)->find();
    }

    /**
     * @param int $userID
     * @param array $updateData
     * @return UsersModel
     */
    public function updateUserInfoByID(int $userID, array $updateData)
    {
        return $this->usersModel::update($updateData, ['id' => $userID, 'status' => 1]);
    }

    /**
     * alterUserPassword
     * @param string $loginPassword
     * @param string $payPassword
     * @return mixed
     */
    public function alterUserPassword(string $loginPassword, string $payPassword)
    {
        return app()->get("userProfile")->save([
            "password" => password_hash($loginPassword, PASSWORD_DEFAULT),
            "payPassword" => password_hash($payPassword, PASSWORD_DEFAULT),
        ]);
    }

    /**
     * getUserAddressByAddressID
     * @param int $userAddressID
     * @return \app\common\model\UserAddressModel|array|mixed|\think\Model|null
     */
    public function getUserAddressByAddressID(int $userAddressID)
    {
        return $this->isValidAddress($userAddressID)?->hidden(["updatedAt", "deletedAt"]);
    }

    /**
     * setUserAddressByDefault
     * @param int $addressID
     * @param int $isDefault
     * @return bool
     */
    public function setUserAddressByDefault(int $addressID, int $isDefault)
    {
        $updateResult = app()->get("userProfile")->shipAddress()->where("id", $addressID)->update(["isDefault" => $isDefault]);

        if ($updateResult && $isDefault == 1) {
            $this->editDefaultProperties($addressID);
        }

        return true;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getWithdrawalAmount(int $id)
    {
        return app()->get("userProfile")->withdrawal()->where("amountType", $id)->find();
    }
}