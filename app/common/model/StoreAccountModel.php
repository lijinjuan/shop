<?php
declare (strict_types=1);

namespace app\common\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class StoreAccountModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_stores_account";

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

    protected function getMonthTimeAttr($value, $data)
    {
        return date('Y-m',strtotime($data['createdAt']));
    }
}
