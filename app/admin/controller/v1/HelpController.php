<?php

namespace app\admin\controller\v1;

use app\admin\repositories\HelpRepositories;
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
     * @throws \app\lib\exception\ParameterException
     */
    public function addHelp(Request $request)
    {
        $data = $request->only(['title','content','sort']);
        return $this->helpRepositories->addHelp($data);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editHelp(int $id,Request $request)
    {
        $data = $request->only(['title','content','sort']);
        return $this->helpRepositories->editHelp($id,$data);
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delHelp(int $id)
    {
       return $this->helpRepositories->delHelp($id);

    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOneByID(int $id)
    {
        return $this->helpRepositories->getHelpDetail($id);
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function helpList(Request $request)
    {
        $pageSize = $request->post('pageSize');
        return $this->helpRepositories->listHelp($pageSize);
    }

}