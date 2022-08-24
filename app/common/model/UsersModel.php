<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\UsersModel
 */
class UsersModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_users";

    /**
     * @var string
     */
    protected $createTime = "createdAt";

    /**
     * @var string
     */
    protected $updateTime = "updatedAt";

    /**
     * @var string
     */
    protected $autoWriteTimestamp = "timestamp";

    /**
     * onBeforeInsert
     * @param \think\Model $model
     * @return mixed
     */
    public static function onBeforeInsert(Model $model): mixed
    {
        $userName = current(explode("@", $model->getAttr("email")));
        $model->setAttr("userName", $userName);
        $model->setAttr("password", password_hash($model->getAttr("password"), PASSWORD_DEFAULT));
        $model->setAttr("userAvatar", "1231231231");
        $model->setAttr("balance", 0.00);
        $model->setAttr("isStore", 0);
        return true;
    }

    /**
     * shipAddress
     * @return \think\model\relation\HasMany
     */
    public function shipAddress()
    {
        return $this->hasMany(UserAddressModel::class, "userID", "id");
    }

    /**
     * store
     * @return \think\model\relation\HasOne
     */
    public function store()
    {
        return $this->hasOne(StoresModel::class, "userID", "id");
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function shoppingCart()
    {
        return $this->hasMany(UsersShoppingCartModel::class, 'userID', 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function withdrawal()
    {
        return $this->hasMany(UsersAmountModel::class, 'userID', 'id');
    }

    /**
     * storeAccount
     * @return \think\model\relation\HasMany
     */
    public function storeAccount()
    {
        return $this->hasMany(StoreAccountModel::class, "userID", "id");
    }

}