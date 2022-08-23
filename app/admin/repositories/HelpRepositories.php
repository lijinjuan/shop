<?php

namespace app\admin\repositories;

use app\lib\exception\ParameterException;
use think\Request;

class HelpRepositories extends AbstractRepositories
{
    /**
     * @param array $data
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function addHelp(array $data)
    {
        $this->servletFactory->helpServ()->addHelp($data);
        return renderResponse();
    }

    /**
     * @param int $id
     * @param array $data
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editHelp(int $id, array $data)
    {
        $help = $this->servletFactory->helpServ()->getOneHelp($id);
        if (!$help) {
            throw new ParameterException(['errMessage' => '该条帮助中心记录不存在...']);
        }
        $help::update($data, ['id' => $id]);
        return renderResponse();
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delHelp(int $id)
    {
        $help = $this->servletFactory->helpServ()->getOneHelp($id);
        if (!$help) {
            throw new ParameterException(['errMessage' => '该条帮助中心记录不存在...']);
        }
        $help->delete();
        return renderResponse();
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getHelpDetail(int $id)
    {
        $help = $this->servletFactory->helpServ()->getOneHelp($id);
        if (!$help) {
            throw new ParameterException(['errMessage' => '该条帮助中心记录不存在...']);
        }
        return renderResponse($help);
    }

    /**
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function listHelp(int $pageSize)
    {
        return renderPaginateResponse($this->servletFactory->helpServ()->helpList($pageSize));

    }
}