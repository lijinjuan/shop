<?php

namespace app\common\model;

use think\Model;

/**
 * \app\common\model\GoodsModel
 */
class GoodsModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_goods";

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
     * @return \think\model\relation\HasMany
     */
    public function goodsSku()
    {
        return $this->hasMany(GoodsSkuModel::class, 'goodsID', 'id')->field(['id', 'goodsID', 'skuName', 'sku', 'skuImg', 'skuStock', 'saleAmount', 'skuDiscountPrice', 'skuPrice', 'createdAt']);
    }

    /**
     * category
     * @return \think\model\relation\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(CategoryModel::class, "categoryID", "id");
    }

    /**
     * brands
     * @return \think\model\relation\BelongsTo
     */
    public function brands()
    {
        return $this->belongsTo(BrandsModel::class, "brandID", "id");
    }

}