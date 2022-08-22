<?php

namespace app\admin\servlet;

use app\common\model\UsersModel;

/**
 * \app\admin\servlet\UsersServlet
 */
class UsersServlet
{

    /**
     * @var UsersModel
     */
    protected UsersModel $usersModel;

    /**
     * @param UsersModel $usersModel
     */
    public function __construct(UsersModel $usersModel)
    {
        $this->usersModel = $usersModel;
    }

    /**
     * @param int $type
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function userList(int $type,int $pageSize)
    {
        //1->普通会员 2->店铺
        $select = [
            'id','userName','email','userAvatar','status','lastIP'
        ];
        $hidden = [
            'store.deletedAt','store.updatedAt','store.parentStoreID','store.checkID','store.checkAt','store.reason'
        ];
        return $this->usersModel->where('isStore', (int)$type - 1)->field($select)->with(['store'])->hidden($hidden)->order('createdAt','desc')->paginate($pageSize);

    }

    /**
     * @param int $id
     * @return UsersModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserInfoByID(int $id)
    {
        return $this->usersModel->where('id',$id)->with(['store'=>function($query){
            $query->field(['id','userID','storeName','storeRemark','isRealPeople','storeLevel','creditScore']);
        }])->hidden(['deletedAt'])->find();
    }


}