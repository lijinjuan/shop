<?php

namespace app\api\repositories;

use app\common\model\OrdersDetailModel;
use app\common\model\StoresModel;
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
            $storeInfo = $this->checkStore($storeID);
            if (is_null($storeInfo)) {
                throw  new ParameterException(['errMessage' => '店铺不存在...']);
            }
        }
        //检测商品
        $newGoodsInfo = $this->checkGoods($goodsInfo);
        //检测库存
        $this->checkStock($goodsInfo);
        $orderSn = $this->makeOrderData($newGoodsInfo, $goodsInfo, $isValid, $storeInfo);
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
     * @param StoresModel $storeInfo
     * @return string
     */
    protected function makeOrderData(array $newGoodsInfo, array $goodsInfo, UserAddressModel $addressInfo, StoresModel $storeInfo)
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
        $order_info = $this->makeOrderInfo($orderSn, $trade_order_info, $addressInfo, $storeInfo);
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
            $totalPrice = bcmul($goodsPrice, (string)$val['number'], 2);
            $goods_order_info[$key2]['goodsTotalPrice'] = $totalPrice;
            $goods_order_info[$key2]['goodsCommission'] = 0.00;
        }
        return $goods_order_info;
    }

    protected function makeOrderInfo(string $orderSn, array $orderInfo, UserAddressModel $addressInfo, StoresModel $storeInfo)
    {
        //总订单数据
        $order_info['orderNo'] = $orderSn;
        $order_info['userID'] = app()->get('userProfile')->id;
        $order_info['storeID'] = $storeInfo->id;
        $order_info['agentID'] = $storeInfo->agentID;
        $order_info['agentAmount'] = $storeInfo->agentName;
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
            //清空购物车数据
            $cartData = $order->goodsDetail()->where('orderNo', $order->orderNo)->field(['id', 'goodsID', 'skuID'])->select()->toArray();
            $this->servletFactory->usersShoppingCartServ()->clearChoppingCart(app()->get('userProfile')->id, $cartData);
            $commission = $this->servletFactory->commissionServ()->getCommissionByType(2);
            $goodsCommission = json_decode($commission->content, true);
            if ($goodsCommission) {
                $goodsCommission = $goodsCommission[0]['goodsCommission'];
            } else {
                $goodsCommission = 0;
            }
            $updateData = [
                'userPayPrice' => $order->goodsTotalPrice,
                'orderStatus' => !empty($order->storeID) ? 1 : 2,
                'orderCommission' => $order->storeID ?? sprintf('%.2f', round($order->goodsTotalPrice * ($goodsCommission / 100), 2)),
                'userPayAt' => date('Y-m-d H:i:s'),
                'userPayStyle' => '余额支付',
            ];
            $order::update($updateData, ['id' => $order->id]);
            foreach ($order->goodsDetail as $detail){
                /**
                 * @var OrdersDetailModel $detail;
                 */
                $detail->where('id',$detail->id)->update(['status'=> !empty($order->storeID) ? 1 : 2,'goodsCommission'=>sprintf('%.2f', round($detail->goodsTotalPrice * ($goodsCommission / 100), 2))]);
            }
            //减库存 增销量
            $order->goodsSku?->each($this->addSalesAmount());
            $order->save();
            //更新账变记录（商户或平台）
            if ($order->storeID) {
                $account = [
                    'title' => '支付订单',
                    'balance' => sprintf('%.2f', round(app()->get('userProfile')->balance + $order->userPayPrice, 2)),
                    'storeID' => $order->storeID,
                    'userID' => $order->userID,
                    'changeBalance' => $order->userPayPrice,
                    'action' => 1,
                    'type' => 5,//支付订单，用户购买商品
                ];
                $this->servletFactory->storeAccountServ()->addAccount($account);
            } else {
                //type 1->消费（购买商品）2->充值 3->提现 4->佣金 5->推广 6->订单退款
                $balance = $this->servletFactory->adminBalanceServ()->getBalance();
                $account = ['type' => 1, 'userID' => $order->userID, 'title' => '支付订单', 'balance' => sprintf('%.2f', round($balance->balance + $order->userPayPrice, 2)), 'changeBalance' => $order->userPayPrice, 'remark' => '', 'action' => 1];
                $this->servletFactory->adminAccountServ()->addAdminAccount($account);
            }
            //发送站内信
            $this->servletFactory->messageServ()->addMessage(['title' => '购买成功通知', 'content' => sprintf('您购买的商品已成功付款，付款金额为%s美元，订单号为%s。谢谢您的到来！', $order->goodsTotalPrice, $order->orderNo), 'userID' => app()->get('userProfile')->id]);
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
     * @param int $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function orderDetailByID(int $id)
    {
        return renderResponse($this->servletFactory->orderDetailServ()->getDetailByID($id));
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
        Db::transaction(function () use ($detail, $refundData) {
            $refundData['userID'] = app()->get('userProfile')->id;
            $refundData['orderSn'] = makeOrderNo();
            $refundData['goodsName'] = $detail->goodsName;
            $refundData['goodsPrice'] = $detail->goodsPrice;
            $refundData['goodsCover'] = $detail->skuImage;
            $refundData['goodsNum'] = $detail->goodsNum;
            $refundData['goodsTotalPrice'] = sprintf('%.2f', round($detail->goodsPrice * $detail->goodsNum, 2));
            $refundData['goodsSku'] = $detail->skuName;
            $this->servletFactory->refundServ()->addRefund(array_filter($refundData));
            $detail::update(['status' => 6], ['id' => $detail->id]);
            $detail->orders()->update(['orderStatus' => 6]);
        });
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
            $order::update(['orderStatus' => 4], ['orderNo' => $orderSn]);
            $order->goodsDetail()->update(['status' => 4]);
            //发送站内信
            $allGoodsName = [];
            foreach ($order->goodsDetail as $item) {
                $allGoodsName[] = $item['goodsName'];
            }
            $allGoodsNameString = implode(',', $allGoodsName);
            $this->servletFactory->messageServ()->addMessage(['title' => '确认收货通知', 'content' => sprintf('亲爱的，您的订单%s和商品%s已确认收货，谢谢您的到来！', $order->orderNo, $allGoodsNameString), 'userID' => app()->get('userProfile')->id]);
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
            $order::update(['orderStatus' => -1], ['orderNo' => $orderSn]);
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