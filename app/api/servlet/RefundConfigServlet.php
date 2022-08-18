<?php

namespace app\api\servlet;

use app\common\model\RefundConfigModel;
use app\common\model\RefundModel;

class RefundConfigServlet
{
    /**
     * @var RefundConfigModel
     */
    protected RefundConfigModel $refundConfigModel;

    /**
     * @param RefundConfigModel $refundConfigModel
     */
    public function __construct(RefundConfigModel $refundConfigModel)
    {
        $this->refundConfigModel = $refundConfigModel;
    }


    /**
     * @param int $id
     * @return RefundConfigModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getConfigByID(int $id)
    {
        return $this->refundConfigModel->where('type',$id)->field(['id','content'])->select();
    }


}