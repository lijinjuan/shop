<?php

namespace app\admin\servlet;

use app\common\model\GoodsSkuModel;
use app\lib\exception\ParameterException;

/**
 * \app\admin\servlet\GoodsSkuServlet
 */
class GoodsSkuServlet
{
    /**
     * @var \app\common\model\GoodsSkuModel
     */
    protected GoodsSkuModel $goodsSkuModel;

    /**
     * @param \app\common\model\GoodsSkuModel $goodsSkuModel
     */
    public function __construct(GoodsSkuModel $goodsSkuModel)
    {
        $this->goodsSkuModel = $goodsSkuModel;
    }

    /**
     * getGoodsSkuDetailBySkuID
     * @param int $skuID
     * @param bool $passable
     * @return \app\common\model\GoodsSkuModel|array|mixed|\think\Model|null
     */
    public function getGoodsSkuDetailBySkuID(int $skuID, bool $passable = true)
    {
        $skuDetail = $this->goodsSkuModel->where("id", "$skuID")->find();

        if (is_null($skuDetail) && $passable) {
            throw new ParameterException(["errMessage" => "规则错误或者已被删除..."]);
        }

        return $skuDetail;
    }

}