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
        $model = $this->refundModel->where('userID', app()->get('userProfile')->id)->where('orderID', $refundData['orderID'])->find();
        if (!$model) {
            if (!empty($refundData['voucherImg'])) {
                $refundData['voucherImg'] = json_encode($refundData['voucherImg']);
            }
            $model = $this->refundModel::create($refundData);
        }
        return $model;
    }

    /**
     * @param int $status
     * @return RefundModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refundList(int $type)
    {
        $select = ['id', 'orderSn', 'orderID','goodsName', 'goodsPrice', 'goodsCover', 'goodsNum', 'goodsTotalPrice', 'goodsSku'];
        $model = $this->refundModel->where('userID', app()->get('userProfile')->id);
        if ($type == 1) {
            $model->whereIn('status', [0, 1]);
        } elseif ($type == 2) {
            $model->where('status', 0);
        } else {
            $model->where('status', 1);
        }
        return $model->field($select)->select();
    }



}