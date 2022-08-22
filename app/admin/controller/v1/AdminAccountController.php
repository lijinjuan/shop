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
        $search = $request->only(['storeName','startTime','endTime']);
        return $this->adminAccountRepositories->accountList($search,$pageSize);

    }

}