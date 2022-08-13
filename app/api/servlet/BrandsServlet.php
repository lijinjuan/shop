<?php

namespace app\api\servlet;

use app\common\model\BrandsModel;

/**
 * \app\api\servlet\BrandsServlet
 */
class BrandsServlet
{

    /**
     * @var \app\common\model\BrandsModel
     */
    protected BrandsModel $brandsModel;

    /**
     * @param \app\common\model\BrandsModel $brandsModel
     */
    public function __construct(BrandsModel $brandsModel)
    {
        $this->brandsModel = $brandsModel;
    }

    /**
     * getBrandsList
     * @return mixed
     */
    public function getBrandsList()
    {
        return $this->brandsModel->where("status", 1)->field(["id", "brandName", "brandLogo", "createdAt"])->order("createdAt", "desc")->select();
    }

}