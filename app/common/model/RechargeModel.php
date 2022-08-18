<?php

namespace app\common\model;

use think\Model;

class RechargeModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_users_recharge";

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


    protected function getRechargeNameAttr()
    {
        return 'USDT';
    }

    protected function getOrderStatusAttr($value,$data)
    {
        $status = [
            0=>'审核中',
            1=>'审核成功',
            2=>'审核失败',
        ];
        return $status[$data['status']]??'审核中';

    }

}