<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\BannersModel
 */
class BannersModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_banners";

    /**
     * @var string
     */
    protected $createTime = "createdAt";

    /**
     * @var string
     */
    protected $updateTime = "updatedAt";

    /**
     * @var bool
     */
    protected $autoWriteTimestamp = true;

    /**
     * items
     * @return \think\model\relation\HasMany
     */
    public function items()
    {
        return $this->hasMany(BannerItemsModel::class, 'bannerID', 'id')->order("sort", "desc")->field(["id", "bannerID", "imgID", "sort", "itemAction", "itemType"]);
    }


}