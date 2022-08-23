<?php

namespace app\admin\servlet;

use app\common\model\RefundConfigModel;

/**
 * \app\admin\servlet\RefundConfigServlet
 */
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
     * @param array $data
     * @return RefundConfigModel|\think\Model
     */
    public function addRefundConfig(array $data)
    {
        return $this->refundConfigModel::create($data);
    }


    /**
     * @param int $id
     * @param array $updateData
     * @return RefundConfigModel
     */
    public function editRefundConfig(int $id, array $updateData)
    {
        return $this->refundConfigModel::update($updateData, ['id' => $id]);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delRefundConfig(int $id)
    {
        return $this->refundConfigModel->where('id', $id)->delete();
    }

    /**
     * @param array $condition
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getRefundConfigList(int $type, array $condition = [])
    {
        return $this->refundConfigModel->where('type', $type)->paginate();
    }

    /**
     * @param int $id
     * @return RefundConfigModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRefundConfigByID(int $id)
    {
        return $this->refundConfigModel->where('id', $id)->select();
    }

    /**
     * getRefundReasonConfigByID
     * @param int $id
     * @param int $type
     * @return \app\common\model\RefundConfigModel|array|mixed|\think\Model|null
     */
    public function getRefundReasonConfigByID(int $id, int $type)
    {
        return $this->refundConfigModel->where('id', $id)->where("type", $type)->find();
    }
}