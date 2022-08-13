<?php

namespace app\api\controller\v1;

use app\api\repositories\BrandsRepositories;

/**
 * \app\api\controller\v1\BrandController
 */
class BrandController
{

    /**
     * @var \app\api\repositories\BrandsRepositories
     */
    protected BrandsRepositories $brandsRepositories;

    /**
     * @param \app\api\repositories\BrandsRepositories $brandsRepositories
     */
    public function __construct(BrandsRepositories $brandsRepositories)
    {
        $this->brandsRepositories = $brandsRepositories;
    }

    /**
     * getBrandsList
     * @return \think\response\Json
     */
    public function getBrandsList()
    {
        return $this->brandsRepositories->getBrandsList();
    }

}