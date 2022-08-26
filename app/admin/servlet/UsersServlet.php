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
     * @param string $userAccount
     * @param int $status
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function userList(int $pageSize, string $userAccount)
    {
        //1->普通会员 2->店铺
        $select = [
            'id', 'userName', 'email', 'userAvatar', 'status', 'lastIP', 'lastLoginTime', 'loginNum', 'remark', 'isRealPeople'
        ];
        $model = $this->usersModel->where('isStore', 0)->field($select);
        if (!empty($userAccount)) {
            $model->where('email', 'like', '%' . $userAccount . '%');
        }
        return $model->order('createdAt', 'desc')->paginate($pageSize);

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
        return $this->usersModel->where('id', $id)->with(['store'])->hidden(['deletedAt'])->find();
    }

    /**
     * getUserConnectionByStoreID
     * @param array $parentsArr
     * @return array|\think\Collection|\think\db\BaseQuery[]
     */
    public function getUserConnectionByStoreID(array $parentsArr)
    {
        return $this->usersModel::hasWhere("store", function ($query) use ($parentsArr) {
            $query->whereIn("id", $parentsArr);
        })->with(["store" => fn($query) => $query->field(["id", "userID"])])->select();
    }

    /**
     * batchUpdateUserBalance
     * @param array $userBalance
     * @return \think\Collection
     */
    public function batchUpdateUserBalance(array $userBalance)
    {
        array_walk($userBalance, fn(&$item) => $item["id"] = $item["userID"]);
        return $this->usersModel->allowField(["id", "balance"])->saveAll($userBalance);
    }

}