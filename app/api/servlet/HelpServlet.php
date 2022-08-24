<?php

namespace app\api\servlet;

use app\common\model\HelpCenterModel;

class HelpServlet
{

    /**
     * @var HelpCenterModel
     */
    protected HelpCenterModel $helpCenterModel;

    /**
     * @param HelpCenterModel $helpCenterModel
     */
    public function __construct(HelpCenterModel $helpCenterModel)
    {
        $this->helpCenterModel = $helpCenterModel;
    }

    /**
     * @param int $pageSize
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function helpList(int $pageSize = 20)
    {
       return $this->helpCenterModel->where('status',1)->paginate($pageSize);
    }

    /**
     * @param int $id
     * @return HelpCenterModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getHelpByID(int $id)
    {
        return $this->helpCenterModel->where('id',$id)->find();
    }

}