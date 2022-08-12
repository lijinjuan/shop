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
     * img
     * @return \think\model\relation\BelongsTo
     */
    public function img()
    {
        return $this->belongsTo(ImagesModel::class, "imgID", 'id');
    }
}