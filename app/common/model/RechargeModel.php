<?php

namespace app\common\model;

use think\Model;
use think\model\relation\HasOne;
use think\session\Store;

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


    protected function getRechargeNameAttr($value, $data)
    {
        $status = [
            1 => 'USDT-ERC20',
            2 => 'USDT-TRC20',
            3 => 'USDT-OMINI'
        ];
        return $status[$data['rechargeType']] ?? 'USDT';
    }

    protected function getOrderStatusAttr($value, $data)
    {
        $status = [
            0 => '审核中',
            1 => '审核成功',
            2 => '审核失败',
        ];
        return $status[$data['status']] ?? '审核中';

    }


    /**
     * @return HasOne
     */
    public function store()
    {
        return $this->hasOne(StoresModel::class, 'id', 'storeID');
    }

    /**
     * @return HasOne
     */
   public function user()
   {
       return $this->hasOne(UsersModel::class,'id','userID');
   }
}