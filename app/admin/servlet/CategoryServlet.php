<?php

namespace app\admin\servlet;

use app\common\model\CategoryModel;
use app\lib\exception\ParameterException;

/**
 * \app\admin\servlet\CategoryServlet
 */
class CategoryServlet
{
    /**
     * @var \app\common\model\CategoryModel
     */
    protected CategoryModel $categoryModel;

    /**
     * @param \app\common\model\CategoryModel $categoryModel
     */
    public function __construct(CategoryModel $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    /**
     * getParentCategoryList
     * @return \app\common\model\CategoryModel[]|array|\think\Collection
     */
    public function getCategoryList(int $parentID = 0)
    {
        return $this->categoryModel->where("parentID", $parentID)->field(["id", "categoryName"])->select();
    }

    /**
     * getCategoryListByPaginate
     * @return \app\common\model\CategoryModel[]|array|\think\Collection
     */
    public function getCategoryListByPaginate()
    {
        return $this->categoryModel->field(["id", "categoryName", "parentID", "categoryImgUrl", "status", "sort", "createdAt"])
            ->order("sort", "desc")
            ->order("createdAt", "desc")
            ->select();
    }

    /**
     * createNewGoodsCategory
     * @param array $categoryInfo
     * @return \app\common\model\CategoryModel|\think\Model
     */
    public function createNewGoodsCategory(array $categoryInfo)
    {
        // 判断parentID
        if ($categoryInfo["parentID"] > 0) {
            $pCategory = $this->getCategoryDetailByCategoryID($categoryInfo["parentID"]);

            if ($pCategory->parentID > 0) {
                throw new ParameterException(["errMessage" => "分类参数异常..."]);
            }
        }

        return $this->categoryModel::create($categoryInfo);
    }

    /**
     * getCategoryDetailByCategoryID
     * @param int $categoryID
     * @param bool $passable
     * @return \app\common\model\CategoryModel|array|mixed|\think\Model|null
     */
    public function getCategoryDetailByCategoryID(int $categoryID, bool $passable = true)
    {
        $categoryDetail = $this->categoryModel->where("id", $categoryID)->where("status", 1)->find();

        if (is_null($categoryDetail) && $passable) {
            throw new ParameterException(["errMessage" => "商品分类不存在或已被删除..."]);
        }

        return $categoryDetail;
    }

}