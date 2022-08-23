<?php

namespace app\admin\servlet;

use app\common\model\RefundModel;
use app\lib\exception\ParameterException;

/**
 * \app\admin\servlet\RefundServlet
 */
class RefundServlet
{

    /**
     * @var \app\common\model\RefundModel
     */
    protected RefundModel $refundModel;

    /**
     * @param \app\common\model\RefundModel $refundModel
     */
    public function __construct(RefundModel $refundModel)
    {
        $this->refundModel = $refundModel;
    }

    /**
     * getRefundDetailByID
     * @param int $id
     * @param int $status
     * @param bool $passable
     * @return \app\common\model\RefundModel|array|mixed|\think\Model|null
     */
    public function getRefundDetailByID(int $id, int $status, bool $passable = true)
    {
        $refundDetail = $this->refundModel->where("id", $id)->where("status", $status)->find();

        if (is_null($refundDetail) && $passable) {
            throw new ParameterException(["errMessage" => "退款的订单异常..."]);
        }

        return $refundDetail;
    }


}