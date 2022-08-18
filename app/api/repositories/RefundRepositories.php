<?php

namespace app\api\repositories;

class RefundRepositories extends AbstractRepositories
{
    /**
     * @param int $status
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRefundList(int $status)
    {
        return renderResponse($this->servletFactory->refundServ()->refundList($status));
    }

}