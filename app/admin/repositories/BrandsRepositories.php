<?php

namespace app\admin\repositories;

use app\lib\exception\ParameterException;

/**
 * \app\admin\repositories\BrandsRepositories
 */
class BrandsRepositories extends AbstractRepositories
{
    /**
     * getGoodsBrandsList
     * @return \think\response\Json
     */
    public function getGoodsBrandsList()
    {
        $brandList = $this->servletFactory->brandsServ()->getBrandsList();
        return renderResponse($brandList);
    }

    /**
     * getBrandsListByPaginate
     * @return \think\response\Json
     */
    public function getBrandsListByPaginate()
    {
        $brandList = $this->servletFactory->brandsServ()->getBrandsListByPaginate();
        return renderPaginateResponse($brandList);
    }

    /**
     * getBrandDetailByID
     * @param int $brandID
     * @return \think\response\Json
     */
    public function getBrandDetailByID(int $brandID)
    {
        $brandDetail = $this->servletFactory->brandsServ()->getBrandsDetailByID($brandID);
        return renderResponse($brandDetail);
    }

    /**
     * createBrandsDetail
     * @param array $brandsInfo
     * @return \think\response\Json
     */
    public function createBrandsDetail(array $brandsInfo)
    {
        $this->servletFactory->brandsServ()->createBrandsEntity($brandsInfo);
        return renderResponse();
    }

    /**
     * editBrandsDetail
     * @param int $brandID
     * @param array $brandsInfo
     * @return \think\response\Json
     */
    public function editBrandsDetail(int $brandID, array $brandsInfo)
    {
        $brandDetail = $this->servletFactory->brandsServ()->getBrandsDetailByID($brandID);
        $brandDetail->allowField(["brandName", "brandLogo", "sort"])->save($brandsInfo);
        return renderResponse();
    }

    /**
     * deleteBrandsDetailByBrandID
     * @param int $brandID
     * @return \app\common\model\BrandsModel|array|mixed|\think\Model|null
     */
    public function deleteBrandsDetailByBrandID(int $brandID)
    {
        $brandDetail = $this->servletFactory->brandsServ()->getBrandsDetailByID($brandID);

        if (!$brandDetail->goods->isEmpty()) {
            throw new ParameterException(["errMessage" => "该品牌下存在商品，暂时不能删除..."]);
        }

        $brandDetail->delete();

        return $brandDetail;
    }
}