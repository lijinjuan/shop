<?php

namespace app\admin\controller\v1;

use app\admin\repositories\RefundConfigRepositories;
use think\Request;

class RefundConfigController
{
    /**
     * @var RefundConfigRepositories
     */
    protected RefundConfigRepositories $refundConfigRepositories;

    /**
     * @param RefundConfigRepositories $refundConfigRepositories
     */
    public function __construct(RefundConfigRepositories $refundConfigRepositories)
    {
        $this->refundConfigRepositories = $refundConfigRepositories;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function addRefundConfig(Request $request)
    {
        //1->退款类型 2->退款原因
        $content = $request->post('content');
        $type = $request->post('type');
       return  $this->refundConfigRepositories->addRefundConfig(compact('content','type'));
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
    public function editRefundConfig(int $id,Request $request)
    {
        $content = $request->put('content');
        return $this->refundConfigRepositories->editRefundConfig($id,['content'=>$content]);

    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delRefundConfig(int $id)
    {
        return $this->refundConfigRepositories->delRefundConfig($id);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function refundConfigList(int $type)
    {
        return $this->refundConfigRepositories->getRefundConfigList($type);
    }


}