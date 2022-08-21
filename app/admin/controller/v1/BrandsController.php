<?php

namespace app\admin\controller\v1;

use app\admin\repositories\BrandsRepositories;
use think\Request;

/**
 * \app\admin\controller\v1\BrandsController
 */
class BrandsController
{

    /**
     * @var \app\admin\repositories\BrandsRepositories
     */
    protected BrandsRepositories $brandsRepositories;

    /**
     * @param \app\admin\repositories\BrandsRepositories $brandsRepositories
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
        return $this->brandsRepositories->getGoodsBrandsList();
    }

    /**
     * getBrandsListByPaginate
     * @return \think\response\Json
     */
    public function getBrandsListByPaginate()
    {
        return $this->brandsRepositories->getBrandsListByPaginate();
    }

    /**
     * getBrandDetailByBrandID
     * @param int $brandID
     * @return \think\response\Json
     */
    public function getBrandDetailByBrandID(int $brandID)
    {
        return $this->brandsRepositories->getBrandDetailByID($brandID);
    }

    /**
     * createBrands
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function createBrands(Request $request)
    {
        $brandsInfo = $request->only(["brandName", "brandLogo", "sort"]);
        return $this->brandsRepositories->createBrandsDetail($brandsInfo);
    }

    /**
     * editBrandsDetailByBrandID
     * @param int $brandID
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function editBrandsDetailByBrandID(int $brandID, Request $request)
    {
        $brandsInfo = $request->only(["brandName", "brandLogo", "sort"]);
        return $this->brandsRepositories->editBrandsDetail($brandID, $brandsInfo);
    }

    /**
     * deleteBrandsDetailByBrandID
     * @param int $brandID
     * @return \app\common\model\BrandsModel|array|mixed|\think\Model|null
     */
    public function deleteBrandsDetailByBrandID(int $brandID)
    {
        return $this->brandsRepositories->deleteBrandsDetailByBrandID($brandID);
    }

}