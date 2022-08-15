<?php

namespace app\common\model;

use think\Model;

class UsersShoppingCartModel extends  Model
{
    /**
     * @var string
     */
    protected $table = 's_users_shopping_cart';

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
     * @return \think\model\relation\BelongsTo
     */
    public function goods()
    {
        return $this->belongsTo(GoodsModel::class, 'goodsID', 'id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function sku()
    {
        return $this->belongsTo(GoodsSkuModel::class, 'skuID', 'id');
    }


}