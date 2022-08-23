<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\BannerItemsModel
 */
class BannerItemsModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_banner_items";

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
     * @var string[]
     */
    protected $hidden = ["updatedAt", "deletedAt"];
    
    /**
     * @return \think\model\relation\HasOne
     */
    public function banner()
    {
        return $this->hasOne(BannersModel::class,'id','bannerID');
    }
}