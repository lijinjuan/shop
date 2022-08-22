<?php

namespace app\admin\repositories;

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

}