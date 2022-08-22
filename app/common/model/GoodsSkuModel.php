<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class GoodsSkuModel extends Model
{
    use SoftDelete;

    /**
     * @var string
     */
    protected $table = "s_goods_sku";

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
     * @return \think\model\relation\BelongsTo
     */
    public function goods(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(GoodsModel::class, 'goodsID', 'id');
    }

}