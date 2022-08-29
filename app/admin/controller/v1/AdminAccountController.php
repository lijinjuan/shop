<?php

namespace app\admin\controller\v1;

use app\admin\repositories\AdminAccountRepositories;
use think\Request;

class AdminAccountController
{
    /**
     * @var AdminAccountRepositories
     */
    protected AdminAccountRepositories $adminAccountRepositories;

    /**
     * @param AdminAccountRepositories $adminAccountRepositories
     */
    public function __construct(AdminAccountRepositories $adminAccountRepositories)
    {
        $this->adminAccountRepositories = $adminAccountRepositories;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function accountList(Request $request)
    {
        $pageSize = $request->post('pageSize',20);
        $search = $request->only(['storeName','startTime','endTime','ID','userAccount','agentAccount']);
        return $this->adminAccountRepositories->accountList($search,$pageSize);

    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function modifyPassword(Request $request)
    {
        $data = $request->only(['oldPassword', 'newPassword']);
        return $this->adminAccountRepositories->changeAdminPassword($data);

    }

}