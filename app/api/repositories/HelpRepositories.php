<?php

namespace app\api\repositories;

class HelpRepositories extends AbstractRepositories
{
    /**
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function helpList(int $pageSize)
    {
       return renderPaginateResponse($this->servletFactory->helpServ()->helpList($pageSize));
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function helpDetail(int $id)
    {
        return renderResponse($this->servletFactory->helpServ()->getHelpByID($id));
    }

}