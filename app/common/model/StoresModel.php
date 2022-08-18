<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\StoresModel
 */
class StoresModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_stores";

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

    public function getStatusNameAttr($value,$data)
    {
        $status = [
            0=>'待审核',
            1=>'审核通过',
            2=>'审核失败',
            3=>'冻结'
        ];
        return $status[$data['status']];
    }
    /**
     * goods
     * @return \think\model\relation\BelongsToMany
     */
    public function goods()
    {
        return $this->belongsToMany(GoodsModel::class, GoodsStoreModel::class, "goodsID", "storesID");
    }

    /**
     * user
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(UsersModel::class, "userID", "id");
    }

    /**
     * getParentIDAttr
     * @param $value
     * @param $data
     * @return int
     */
    public function getParentIDAttr($value, $data)
    {
        if ($data["parentStoreID"] == ",")
            return 0;

        $parentStoreID = trim($data["parentStoreID"], ",");
        $parentArr = explode(",", $parentStoreID);
        return (int)end($parentArr);
    }

    /**
     * storeAccount
     * @return \think\model\relation\HasMany
     */
    public function storeAccount()
    {
        return $this->hasMany(StoreAccountModel::class, "storeID", "id");
    }

    /**
     * orders
     * @return \think\model\relation\HasMany
     */
    public function orders()
    {
        return $this->hasMany(OrdersModel::class, "storeID", "id");
    }

}