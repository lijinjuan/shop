<?php

namespace app\common\model;

use think\Model;
use think\model\relation\HasOne;

class WithdrawalModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_users_withdrawal";

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
     * @return HasOne
     */
    public function store()
    {
        return $this->hasOne(StoresModel::class, 'id', 'storeID');
    }

    public function getWithdrawalTypeNameAttr($value, $data)
    {
        $withdrawalType = [
            1 => '银行卡',
            2 => 'TRC20',
            3 => 'ERC20'

        ];
        return $withdrawalType[$data['withdrawalType']];
    }



    /**
     * @return HasOne
     */
    public function usersAmount()
    {
        return $this->hasOne(UsersAmountModel::class,'userID','userID');
    }
}