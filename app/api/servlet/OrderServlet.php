<?php

namespace app\api\servlet;

use app\common\model\OrdersModel;
use app\lib\exception\ParameterException;


class OrderServlet
{
    /**
     * @var OrdersModel
     */
    protected OrdersModel $ordersModel;

    /**
     * @param OrdersModel $ordersModel
     */
    public function __construct(OrdersModel $ordersModel)
    {
        $this->ordersModel = $ordersModel;
    }

    /**
     * @param array $orderData
     * @return OrdersModel|\think\Model
     */
    public function addOrder(array $orderData)
    {
        return $this->ordersModel::create($orderData);
    }

    /**
     * @param string $orderSn
     * @return OrdersModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderDetailByID(string $orderSn)
    {
        return $this->ordersModel->where('orderNo', $orderSn)->find();
    }

    /**
     * @param string $orderSn
     * @param array $updateData
     * @return OrdersModel
     */
    public function editOrderByID(string $orderSn, array $updateData)
    {
        return $this->ordersModel::update($updateData, ['orderSn' => $orderSn]);
    }

    /**
     * @param int|array $status
     * @return OrdersModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderList(int|array $status)
    {
        $model = $this->ordersModel->where('userID', app()->get('userProfile')->id);
        if (is_int($status)) {
            $model->where('orderStatus', $status);
        } else {
            $model->whereIn('orderStatus', $status);
        }
        return $model->with(['goodsDetail'])->order('createdAt', 'desc')->field(['id','orderNo','goodsTotalPrice','goodsNum','orderStatus','createdAt'])->hidden(['goodsDetail.createdAt','goodsDetail.updatedAt','goodsDetail.userID','goodsDetail.storeID','goodsDetail.skuID','goodsDetail.skuName'])->select();
    }

    /**
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function orderCount()
    {
        $noPay = $this->ordersModel->where('userID', app()->get('userProfile')->id)->where('orderStatus', 0)->count();
        $noDelivery = $this->ordersModel->where('userID', app()->get('userProfile')->id)->whereIn('orderStatus', [1, 2])->count();
        $noReceived = $this->ordersModel->where('userID', app()->get('userProfile')->id)->where('orderStatus', 3)->count();
        $received = $this->ordersModel->where('userID', app()->get('userProfile')->id)->where('orderStatus', 4)->count();
        $refund = $this->ordersModel->where('userID', app()->get('userProfile')->id)->whereIn('orderStatus', [6, 7])->count();
        $totalOrder = $this->ordersModel->where('userID',app()->get('userProfile')->id)->where('orderStatus','>=',0)->count();
        $totalOrderPrice = sprintf('%.2f',round($this->ordersModel->where('userID',app()->get('userProfile')->id)->where('orderStatus','>=',0)->sum('goodsTotalPrice'),2));
        return compact('noPay', 'noDelivery', 'noReceived', 'received', 'refund','totalOrder','totalOrderPrice');
    }

    /**
     * @param string $orderNo
     * @return OrdersModel|array|mixed|\think\Model|null
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderDetail(string $orderNo): mixed
    {
        $orderModel = $this->ordersModel->where('orderNo',$orderNo)->where('userID',app()->get('userProfile')->id)->find();
        if (!$orderModel){
            throw new ParameterException(['errMessage'=>'订单不存在...']);
        }
        return $orderModel->where('orderNo',$orderNo)->with(['goodsDetail'])->field(['id','orderNo','orderStatus','goodsNum','createdAt'])->hidden(['goodsDetail.createdAt','goodsDetail.updatedAt','goodsDetail.userID','goodsDetail.storeID','goodsDetail.skuID','goodsDetail.skuName'])->find();
    }


}