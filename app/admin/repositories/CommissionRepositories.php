<?php

namespace app\admin\repositories;

use app\lib\exception\ParameterException;

class CommissionRepositories extends AbstractRepositories
{

    /**
     * @param array $data
     * @param int $type
     * @return \think\response\Json
     */
    public function addCommission(array $data, int $type)
    {
        $content = json_encode($data);
        $commission = $this->servletFactory->commissionServ()->getCommissionByType($type);
        if ($commission){
            $commission->content = $content;
            $commission->save();
        }else{
            $this->servletFactory->commissionServ()->addCommission(['content' => $content, 'type' => $type]);
        }
        return renderResponse();
    }

    /**
     * @param int $type
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCommission(int $type)
    {
        if (!in_array($type, [1, 2])) {
            throw new ParameterException(['errMessage' => '参数错误...']);
        }
        return renderResponse($this->servletFactory->commissionServ()->getCommissionByType($type));
    }
}