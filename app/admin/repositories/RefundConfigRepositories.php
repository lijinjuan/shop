<?php

namespace app\admin\repositories;

use app\lib\exception\ParameterException;

class RefundConfigRepositories extends AbstractRepositories
{
    /**
     * @param array $data
     * @return \think\response\Json
     */
    public function addRefundConfig(array $data)
    {
        $this->servletFactory->refundConfigServ()->addRefundConfig($data);
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
    public function editRefundConfig(int $id, array $data)
    {
        $refund = $this->servletFactory->refundConfigServ()->getRefundConfigByID($id);
        if (!$refund){
            throw new ParameterException(['errMessage'=>'配置不存在']);
        }
        $this->servletFactory->refundConfigServ()->editRefundConfig($id,$data);
        return renderResponse();
    }

    /**
     * @param int $type
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getRefundConfigList(int $type)
    {
        return renderPaginateResponse($this->servletFactory->refundConfigServ()->getRefundConfigList($type));
    }

    /**
     * @param int $id
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delRefundConfig(int $id)
    {
        $refund = $this->servletFactory->refundConfigServ()->getRefundConfigByID($id);
        if (!$refund){
            throw new ParameterException(['errMessage'=>'配置不存在']);
        }
        $this->servletFactory->refundConfigServ()->delRefundConfig($id);
        return renderResponse();
    }

}