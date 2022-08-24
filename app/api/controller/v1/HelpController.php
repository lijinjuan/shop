<?php

namespace app\api\controller\v1;

use app\api\repositories\HelpRepositories;
use think\Request;

class HelpController
{
    /**
     * @var HelpRepositories
     */
    protected HelpRepositories $helpRepositories;

    /**
     * @param HelpRepositories $helpRepositories
     */
    public function __construct(HelpRepositories $helpRepositories)
    {
        $this->helpRepositories = $helpRepositories;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function helpList(Request $request)
    {
        $pageSize = $request->post('pageSize',10);
        return $this->helpRepositories->helpList($pageSize);
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
        return $this->helpRepositories->helpDetail($id);
    }
}