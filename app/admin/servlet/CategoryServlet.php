<?php

namespace app\admin\servlet;

use app\common\model\CategoryModel;

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

}