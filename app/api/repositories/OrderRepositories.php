<?php

namespace app\api\repositories;

use app\common\model\UserAddressModel;
use app\lib\exception\ParameterException;
use think\db\concern\Transaction;
use think\facade\Db;
use think\facade\Snowflake;

/**
 * \app\api\repositories\OrderRepositories
 */
class OrderRepositories extends AbstractRepositories
{
    /**
     * @param int $addressID
     * @param array $goodsInfo
     * @param int $storeID
     * @return \think\response\Json
     * @throws ParameterException
     */
    public function placeOrder(int $addressID, array $goodsInfo, int $storeID = 0)
    {
        //{'goodsID':1,'skuID':2,'mumber':1},{'goodsID':1,'skuID':2,'mumber':1},
        $isValid = $this->servletFactory->userServ()->isValidAddress($addressID);
        if (!$isValid) {
            throw  new ParameterException();
        }
        if ($storeID) {
            if (!$this->checkStore($storeID)) {
                throw  new ParameterException(['errMessage' => '店铺不存在...']);
            }
        }
        //检测商品
        $newGoodsInfo = $this->checkGoods($goodsInfo);
        //检测库存
        $this->checkStock($goodsInfo);
        $orderSn = $this->makeOrderData($newGoodsInfo, $goodsInfo, $isValid, $storeID);
        //计算店铺佣金，店铺入账记录
        return renderResponse($orderSn);

    }

    /**
     * @param int $storeID
     * @return \app\common\model\StoresModel|array|mixed|\think\Model|null
     * @throws ParameterException
     */
    protected function checkStore(int $storeID)
    {
        return $this->servletFactory->shopServ()->getShopInfoByShopID($storeID);
    }

    /**
     * @param array $goods
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function checkGoods(array $goods)
    {
        $input_goodsIDs = array_column($goods, 'goodsID');
        $goodsInfo = $this->servletFactory->goodsServ()->getGoodsListByGoodsID(array_unique($input_goodsIDs), ['id', 'status', 'goodsName']);
        if (is_null($goodsInfo)) {
            throw new ParametersException(['errMessage' => '商品不存在...']);
        }
        $goodsInfo = $goodsInfo->toArray();
        $newGoodsInfo = [];
        if ($goodsInfo) {
            foreach ($goodsInfo as $item) {
                $newGoodsInfo[$item['id']] = $item;
            }
            foreach ($input_goodsIDs as $item) {
                if (!isset($newGoodsInfo[$item])) {
                    throw new ParametersException(['errMessage' => '商品' . $item . '不存在...']);
                }
                if ($newGoodsInfo[$item]['status'] != 1) {
                    throw new ParametersException(['errMessage' => '商品' . $item['id'] . '已下架...']);
                }
            }
        }
        return $goodsInfo;
    }

    /**
     * @param array $goods
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function checkStock(array $goods)
    {
        $input_skuIDs = array_column($goods, 'number', 'skuID');
        // $skuID=>$number
        $stocksInfo = $this->servletFactory->goodsSkuServ()->getStockByID(array_keys($input_skuIDs));
        if (is_null($stocksInfo)) {
            throw new ParametersException(['errMessage' => '商品规格不存在...']);
        }
        $stocksInfo = $stocksInfo->toArray();
        $newStockInfo = [];
        if ($stocksInfo) {
            foreach ($stocksInfo as $item) {
                $newStockInfo[$item['id']] = $item;
            }
            foreach ($input_skuIDs as $key => $item) {
                if (!isset($newStockInfo[$key])) {
                    throw new ParametersException(['errMessage' => '商品规格' . $item . '不存在...']);
                }
                if ($newStockInfo[$key]['skuStock'] < 1 || $newStockInfo[$key]['skuStock'] < $item) {
                    throw new ParametersException(['errMessage' => '商品' . $newStockInfo[$key]['goodsID'] . '库存不足...']);
                }
            }
        }
        return true;
    }

    /**
     * @param array $newGoodsInfo
     * @param array $goodsInfo
     * @param UserAddressModel $addressInfo
     * @param int $storeID
     * @return string
     */
    protected function makeOrderData(array $newGoodsInfo, array $goodsInfo, UserAddressModel $addressInfo, int $storeID)
    {
        $order_info = $par_goods_info = [];
        $orderSn = $order_info['orderSn'] = makeOrderNo();
        foreach ($newGoodsInfo as $item) {
            if (!empty($item['goodsSku'])) {
                $goodsSku = [];
                foreach ($item['goodsSku'] as $item2) {
                    $goodsSku[$item2['id']] = $item2;
                }
                $item['goodsSku'] = $goodsSku;
            }
            $par_goods_info[$item['id']] = $item;
        }
        //子订单信息
        $trade_order_info = $this->makeGoodsOrder($goodsInfo, $par_goods_info, $orderSn);
        //总订单信息
        $order_info = $this->makeOrderInfo($orderSn, $trade_order_info, $addressInfo, $storeID);
        Db::transaction(function () use ($order_info, $trade_order_info) {
            $orderModel = $this->servletFactory->orderServ()->addOrder($order_info);
            $orderModel->goodsDetail()->saveAll($trade_order_info);
        });

        return $orderSn;
    }

    /**
     * @param array $goods
     * @param array $par_goods_info
     * @param string $orderSn
     * @param int $storeID
     * @return array
     */
    protected function makeGoodsOrder(array $goods, array $par_goods_info, string $orderSn, int $storeID = 0)
    {
        $goods_order_info = [];
        foreach ($goods as $key2 => $val) {
            $goods_order_info[$key2]['userID'] = app()->get('userProfile')->id;
            $goods_order_info[$key2]['orderNo'] = $orderSn;
            $goods_order_info[$key2]['tradeNo'] = Snowflake::generate();
            $goods_order_info[$key2]['goodsID'] = $val['goodsID'];
            $goods_order_info[$key2]['storeID'] = $storeID;
            $goods_order_info[$key2]['skuID'] = $val['skuID'];
            $goods_order_info[$key2]['goodsName'] = $par_goods_info[$val['goodsID']]['goodsName'];
            $goods_order_info[$key2]['skuName'] = $par_goods_info[$val['goodsID']]['goodsSku'][$val['skuID']]['sku'];
            $goods_order_info[$key2]['skuImage'] = $par_goods_info[$val['goodsID']]['goodsSku'][$val['skuID']]['skuImg'];
            $goodsPrice = $par_goods_info[$val['goodsID']]['goodsSku'][$val['skuID']]['skuDiscountPrice'];
            $goods_order_info[$key2]['goodsPrice'] = (string)$goodsPrice;
            $goods_order_info[$key2]['goodsNum'] = $val['number'];
            $goods_order_info[$key2]['goodsTotalPrice'] = bcmul($goodsPrice, (string)$val['number'], 2);
        }
        return $goods_order_info;
    }

    protected function makeOrderInfo(string $orderSn, array $orderInfo, UserAddressModel $addressInfo, int $storeID)
    {
        //总订单数据
        $order_info['orderNo'] = $orderSn;
        $order_info['userID'] = app()->get('userProfile')->id;
        $order_info['storeID'] = $storeID;
        $order_info['agentID'] = 'dd';
        $order_info['agentAmount'] = '代理商';
        $order_info['goodsTotalPrice'] = sprintf('%.2f', round(array_sum(array_column($orderInfo, 'goodsTotalPrice')), 2));
        $order_info['orderStatus'] = 0;
        $order_info['goodsNum'] = array_sum(array_column($orderInfo, 'goodsNum'));
        $order_info['orderCommission'] = 0.00;
        $order_info['userPayPrice'] = 0.00;
        $order_info['userPayStyle'] = '';
        $order_info['userPayAt'] = NULL;
        $order_info['receivingID'] = (int)$addressInfo->id;
        $order_info['receiver'] = $addressInfo->receiver;
        $order_info['receiverMobile'] = $addressInfo->mobile;
        $order_info['receiverAddress'] = $addressInfo->address;
        return $order_info;
    }

    /**
     * @param string $orderSn
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function payment(string $orderSn)
    {
        $order = $this->servletFactory->orderServ()->getOrderDetailByID($orderSn);
        if (is_null($order)) {
            throw new ParameterException(['errMessage' => '订单不存在...']);
        }
        if ($order->orderStatus != 0) {
            throw new ParameterException(['errMessage' => '订单已支付请不要重复支付...']);
        }
        if ($order->userID != app()->get('userProfile')->id) {
            throw new ParameterException(['errMessage' => '不能支付别人的订单...']);
        }
        if (app()->get('userProfile')->balance < $order->goodsTotalPrice) {
            throw new ParameterException(['errMessage' => '账户余额不足支付失败...']);
        }
        Db::transaction(function () use ($orderSn, $order) {
            $this->servletFactory->userServ()->updateUserInfoByID(app()->get('userProfile')->id, ['balance' => bcsub(app()->get('userProfile')->balance - $order->goodsTotalPrice, 2)]);
            $commission = $this->servletFactory->commissionServ()->getCommissionByType(2);
            $goodsCommission = json_decode($commission->content, true);
            $goodsCommission = $goodsCommission['goodsCommission'];
            $updateData = [
                'userPayPrice' => $order->goodsTotalPrice,
                'orderStatus' => 1,
                'orderCommission' => $order->storeID ?? sprintf('%.2f', round($order->goodsTotalPrice * ($goodsCommission / 100), 2)),
                'userPayAt' => date('Y-m-d H:i:s'),
                'userPayStyle' => '余额支付',
            ];
            $order::update($updateData, ['id' => $order->id]);
            $order->goodsDetail()->where('orderNo', $order->orderNo)->update(['status' => 1]);
            //减库存 增销量
            $order->goodsSku?->each($this->addSalesAmount());
            $order->save();
        });
        return renderResponse();
    }

    /**
     * @param $goodsSku
     * @return mixed
     */
    protected function calculateSalesAmount($goodsSku)
    {
        $goodsSku->skuStock -= $goodsSku->pivot->goodsNum;
        $goodsSku->goods->goodsStock -= $goodsSku->pivot->goodsNum;
        $goodsSku->saleAmount += $goodsSku->pivot->goodsNum;
        $goodsSku->goods->goodsSalesAmount += $goodsSku->pivot->goodsNum;
        return $goodsSku->save();
    }

    /**
     * @return \Closure
     */
    protected function addSalesAmount()
    {
        return fn($goodsSku) => $this->calculateSalesAmount($goodsSku);
    }

    /**
     * @param int $type
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderList(int $type)
    {
        //0->用户未支付 1->商家待进货（商家待付款）2->待发货 3->待收货 4->已收货 5->已完成 6->退款中 7->已退款
        $status = match ($type) {
            1 => 0,
            2 => [1, 2],
            3 => 3,
            4 => 4,
            5 => [6, 7],
            6 => 6,
            7 => 7,
            8 => 5,
            default => [0, 1, 2, 3]
        };
        return renderResponse($this->servletFactory->orderServ()->orderList($status));
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function orderCount()
    {
        return renderResponse($this->servletFactory->orderServ()->orderCount());
    }

    /**
     * @param string $orderNo
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderDetail(string $orderNo)
    {
        return renderResponse($this->servletFactory->orderServ()->orderDetail($orderNo));
    }


    /**
     * @param array $refundData
     * @return \think\response\Json
     */
    public function orderRefund(array $refundData)
    {
        $detail = $this->servletFactory->orderDetailServ()->getDetailByID((int)$refundData['orderID']);
        if (empty($detail)) {
            throw new ParameterException(['errMessage' => '订单不存在...']);
        }
        $refundData['userID'] = app()->get('userProfile')->id;
        $refundData['orderSn'] = makeOrderNo();
        $refundData['goodsName'] = $detail->goodsName;
        $refundData['goodsPrice'] = $detail->goodsPrice;
        $refundData['goodsCover'] = $detail->skuImage;
        $refundData['goodsNum'] = $detail->goodsNum;
        $refundData['goodsTotalPrice'] = sprintf('%.2f',round($detail->goodsPrice * $detail->goodsNum,2));
        $refundData['goodsSku'] = $detail->skuName;
        $this->servletFactory->refundServ()->addRefund(array_filter($refundData));
        return renderResponse();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function storeOrderList(int $type)
    {
        //0->用户未支付 1->商家待进货（商家待付款）2->待发货 3->待收货 4->已收货 5->已完成 6->退款中 7->已退款
        if (!in_array($type, [0, 1, 2, 3, 4, 5])) {
            $type = 1;
        }
        return renderResponse($this->servletFactory->orderServ()->storeOrderList($type - 1));
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function storeOrderCount()
    {
        return renderResponse($this->servletFactory->orderServ()->storeOrderCount());
    }

    /**
     * @param string $orderSn
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editOrderStatusByOrderSn(string $orderSn)
    {
        $order = $this->servletFactory->orderServ()->getOrderDetailByID($orderSn);
        if (!$order) {
            throw new ParameterException(['errMessage' => '订单不存在...']);
        }
        if ($order->orderStatus != 3) {
            throw new ParameterException(['errMessage' => '订单已收货请不要重复收货...']);
        }
        Db::transaction(function () use ($order, $orderSn) {
            $order::update(['orderStatus' => 4],['orderNo' => $orderSn]);
            $order->goodsDetail()->update(['status' => 4]);
        });
        return renderResponse();
    }

    /**
     * @param string $orderSn
     * @return \think\response\Json
     * @throws ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delOrderByOrderSn(string $orderSn)
    {
        $order = $this->servletFactory->orderServ()->getOrderDetailByID($orderSn);
        if (!$order) {
            throw new ParameterException(['errMessage' => '订单不存在...']);
        }
        if ($order->orderStatus != 5) {
            throw new ParameterException(['errMessage' => '当前状态不能删除订单...']);
        }
        Db::transaction(function () use ($order, $orderSn) {
            $order::update(['orderStatus' => -1],['orderNo' => $orderSn]);
            $order->goodsDetail()->update(['status' => -1]);
        });
        return renderResponse();
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRefundReason()
    {
        return renderResponse($this->servletFactory->refundConfigServ()->getConfigByID(2));
    }

    /**
     * @param int $type
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refundList(int $type)
    {
        return renderResponse($this->servletFactory->refundServ()->refundList($type));
    }


}