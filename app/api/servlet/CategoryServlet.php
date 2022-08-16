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
        $categories = $this->categoryModel->where("status", 1)->field(["id", "categoryName", "categoryImgID", "parentID"])->with(["img"])->select()?->toArray() ?? [];
        return assertTreeDatum($categories);
    }

}