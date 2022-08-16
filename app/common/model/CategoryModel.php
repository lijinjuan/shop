<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\CategoryModel
 */
class CategoryModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_categories";

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
    protected $autoWriteTimestamp = "timestamp";

    /**
     * img
     * @return \think\model\relation\BelongsTo
     */
    public function img()
    {
        return $this->belongsTo(ImagesModel::class, "categoryImgID", 'id');
    }
}