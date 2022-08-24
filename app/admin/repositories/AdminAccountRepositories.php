<?php

namespace app\admin\repositories;

use app\lib\exception\ParameterException;

class AdminAccountRepositories extends AbstractRepositories
{

    /**
     * @param array $search
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function accountList(array $search, int $pageSize)
    {
        return renderPaginateResponse($this->servletFactory->adminAccountServ()->accountList($search, $pageSize));
    }

    /**
     * @param array $data
     * @return \think\response\Json
     * @throws ParameterException
     */
    public function changeAdminPassword(array $data)
    {
        $adminModel = $this->servletFactory->adminServ()->getAdminProfileByFields(['id'=>app()->get('adminProfile')->id]);
        if (!$adminModel) {
            throw new ParameterException(['errMessage' => '管理员不存在...']);
        }
        if (!password_verify($data['oldPassword'], $adminModel->password)) {
            throw new ParameterException(['errMessage' => '原密码输入错误...']);
        }
        $adminModel::update($data, ['id' => $adminModel->id]);
        return renderResponse();

    }

}