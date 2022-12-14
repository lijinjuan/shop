<?php

namespace app\api\servlet;

use app\common\model\CategoryModel;

/**
 * \app\api\servlet\CategoryServlet
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
     * getCategoriesByTree
     * @return array
     */
    public function getCategoriesByTree()
    {
        $categories = $this->categoryModel->where("status", 1)->field(["id", "categoryName", "categoryImgUrl", "parentID"])->select()?->toArray() ?? [];
        return assertTreeDatum($categories);
    }

    /**
     * getParentCategoryList
     * @param int $categoryID
     * @return array
     */
    public function getParentCategoryList(int $categoryID)
    {
        $categories = $this->categoryModel->where("status", 1)->where("parentID", $categoryID)->column("id");
        return $categories;
    }

    /**
     * getParentCategoryList
     * @param int $categoryID
     * @return array
     */
    public function getParentCategories(int $categoryID)
    {
        $categories = $this->categoryModel->where("status", 1)->where("parentID", $categoryID)->field(["id", "categoryName", "categoryImgUrl", "parentID"])->select();
        return $categories;
    }
}