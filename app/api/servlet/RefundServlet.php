<?php

namespace app\api\servlet;

use app\common\model\RefundModel;

class RefundServlet
{
    /**
     * @var RefundModel
     */
    protected RefundModel $refundModel;

    /**
     * @param RefundModel $refundModel
     */
    public function __construct(RefundModel $refundModel)
    {
        $this->refundModel = $refundModel;
    }

    /**
     * @param array $refundData
     * @return void
     */
    public function addRefund(array $refundData)
    {
        $model = $this->refundModel->where('userID',app()->get('userProfile')->id)->where('orderID',$refundData['orderID'])->find();
        if (!$model){
            if (!empty($refundData['voucherImg'])){
                $refundData['voucherImg'] = json_encode($refundData['voucherImg']);
            }
            $model = $this->refundModel::create($refundData);
        }
        return $model;
    }


}