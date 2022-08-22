<?php

namespace app\admin\servlet;

use app\common\model\AdminsAccountModel;

class AdminAccountServlet
{
    /**
     * @var AdminsAccountModel
     */
    protected AdminsAccountModel $adminsAccountModel;

    /**
     * @param AdminsAccountModel $adminsAccountModel
     */
    public function __construct(AdminsAccountModel $adminsAccountModel)
    {
        $this->adminsAccountModel = $adminsAccountModel;
    }


    /**
     * @param array $data
     * @return AdminsAccountModel|\think\Model
     */
    public function addAdminAccount(array $data)
    {
        return $this->adminsAccountModel::create($data);
    }

    /**
     * @param array $search
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function accountList(array $search,int $pageSize = 20)
    {
        $model = $this->adminsAccountModel->where('type','>',0);
        if (!empty($search['storeName'])){
            $model->where('storeName','like','%'.$search['storeName'].'%');
        }
        if (!empty($search['startTime'])){
            $model->where('createdAt','>=',$search['startTime']);
        }

        if (!empty($search['endTime'])){
            $model->where('createdAt','<=',$search['endTime']);
        }
        return $model->order('createdAt','desc')->paginate($pageSize);
    }

}