<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * \app\common\model\CategoryModel
 */
class CategoryModel extends Model
{

    use SoftDelete ;

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
     * @var string
     */
    protected $deleteTime = "deletedAt";

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

    /**
     * categories
     * @return \think\model\relation\HasMany
     */
    public function categories()
    {
        return $this->hasMany(CategoryModel::class, "parentID", "id");
    }

    /**
     * goods
     * @return \think\model\relation\HasMany
     */
    public function goods()
    {
        return $this->hasMany(GoodsModel::class, "categoryID", "id");
    }
}