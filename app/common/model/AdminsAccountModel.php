<?php

namespace app\common\model;

use think\Model;

class AdminsAccountModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_admins_account";

    /**
     * @var string
     */
    protected $createTime = "createdAt";


    /**
     * @var bool
     */
    protected $autoWriteTimestamp = "timestamp";

    /**
     * @return \think\model\relation\HasOne
     */
    public function user()
    {
        return $this->hasOne(UsersModel::class,'id','userID');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function store()
    {
        return $this->hasOne(StoresModel::class,'id','storeID');
    }

    /**
     * @return \think\model\relation\HasOne
     */
    public function agent()
    {
        return $this->hasOne(AgentsModel::class,'id','agentID');
    }

}