<?php

namespace app\admin\servlet;

use app\common\model\BrandsModel;
use app\lib\exception\ParameterException;

/**
 * \app\admin\servlet\BrandsServlet
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
     * @return \app\common\model\BrandsModel[]|array|\think\Collection
     */
    public function getBrandsList()
    {
        return $this->brandsModel->where("status", 1)->field(["id", "brandName"])->select();
    }

    /**
     * getBrandsListByPaginate
     * @return \think\Paginator
     */
    public function getBrandsListByPaginate()
    {
        return $this->brandsModel->order("createdAt", "desc")->field(["id", "brandName", "brandLogo", "sort", "status", "createdAt"])
            ->paginate((int)request()->param("pageSize", 20));
    }

    /**
     * getBrandsDetailByID
     * @param int $brandID
     * @param bool $passable
     * @return \app\common\model\BrandsModel|array|mixed|\think\Model|null
     */
    public function getBrandsDetailByID(int $brandID, bool $passable = true)
    {
        $brandDetail = $this->brandsModel->where("id", $brandID)->where("status", 1)->find();

        if (is_null($brandDetail) && $passable) {
            throw new  ParameterException(["errMessage" => "品牌分类不存在或已被删除..."]);
        }

        return $brandDetail;
    }

    /**
     * createBrandsEntity
     * @param array $brandsInfo
     * @return \app\common\model\BrandsModel|\think\Model
     */
    public function createBrandsEntity(array $brandsInfo)
    {
        return $this->brandsModel::create($brandsInfo);
    }

}