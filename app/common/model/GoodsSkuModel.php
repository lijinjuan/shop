<?php

namespace app\common\model;

use think\Model;

class GoodsSkuModel extends Model
{
    public function useSoftDelete(string $field, $condition = null)
    {

    }

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
     * @var bool
     */
    protected $autoWriteTimestamp = "timestamp";

}