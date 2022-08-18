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

}