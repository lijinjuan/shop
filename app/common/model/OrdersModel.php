<?php

namespace app\common\model;

use think\helper\Str;
use think\Model;

class OrdersModel extends Model
{
    /**
     * @var string
     */
    protected $pk = "orderNo";

    /**
     * @var string
     */
    protected $table = "s_orders";

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

    protected function getForeignKey(string $name): string
    {
        if (strpos($name, '\\')) {
            $name = class_basename($name);
        }

        return Str::snake($name) . '_orderNo';
    }

    /**
     * @return \think\model\relation\BelongsToMany
     */
    public function goodsSku()
    {
        return $this->belongsToMany(GoodsSkuModel::class, OrdersDetailModel::class, 'skuID', 'orderNo');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function goodsDetail()
    {
        return $this->hasMany(OrdersDetailModel::class, 'orderNo', 'orderNo');
    }





}