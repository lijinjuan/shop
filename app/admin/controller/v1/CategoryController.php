<?php

namespace app\admin\controller\v1;

use app\admin\repositories\CategoryRepositories;
use think\Request;

/**
 * \app\admin\controller\v1\CategoryController
 */
class CategoryController
{
    /**
     * @var \app\admin\repositories\CategoryRepositories
     */
    protected CategoryRepositories $categoryRepositories;

    /**
     * @param \app\admin\repositories\CategoryRepositories $categoryRepositories
     */
    public function __construct(CategoryRepositories $categoryRepositories)
    {
        $this->categoryRepositories = $categoryRepositories;
    }

    /**
     * getCategoryListByCategoryID
     * @param int|null $categoryID
     * @return \think\response\Json
     */
    public function getCategoryListByCategoryID(Request $request)
    {
        $parentID = $request->param("parentID", 0);
        return $this->categoryRepositories->getCategoryListByCategoryID($parentID);
    }

    /**
     * getCategoryListByCategoryID
     * @param int|null $categoryID
     * @return \think\response\Json
     */
    public function getCategoryList(Request $request)
    {
        return $this->categoryRepositories->getCategoryList();
    }

    /**
     * addGoodsCategory
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function addGoodsCategory(Request $request)
    {
        $categoryInfo = $request->only(["categoryName", "parentID", "categoryImgUrl", "sort"]);
        return $this->categoryRepositories->addGoodsCategory($categoryInfo);
    }

    /**
     * getGoodsCategoryDetail
     * @param int $categoryID
     * @return \think\response\Json
     */
    public function getGoodsCategoryDetail(int $categoryID)
    {
        return $this->categoryRepositories->getCategoryDetailByCategoryID($categoryID);
    }

    /**
     * editGoodsCategory
     * @param int $categoryID
     * @param \think\Request $request
     */
    public function editGoodsCategory(int $categoryID, Request $request)
    {
        $category = $request->only(["categoryName", "parentID", "categoryImgUrl", "sort"]);
        return $this->categoryRepositories->editGoodsCategory($categoryID, $category);
    }

    /**
     * deleteGoodsCategory
     * @param int $categoryID
     * @return \think\response\Json
     */
    public function deleteGoodsCategory(int $categoryID)
    {
        return $this->categoryRepositories->deleteGoodsCategoryByID($categoryID);
    }


}