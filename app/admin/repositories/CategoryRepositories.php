<?php

namespace app\admin\repositories;

use app\lib\exception\ParameterException;
use think\model\Collection;

/**
 * \app\admin\repositories\CategoryRepositories
 */
class CategoryRepositories extends AbstractRepositories
{

    /**
     * getCategoryListByCategoryID
     * @param int $parentID $parentID
     * @return \think\response\Json
     */
    public function getCategoryListByCategoryID(int $parentID = 0)
    {
        $categoryList = $this->servletFactory->categoryServ()->getCategoryList($parentID);
        return renderResponse($categoryList);
    }

    /**
     * getCategoryList
     * @return \think\response\Json
     */
    public function getCategoryList()
    {
        $categoryList = $this->servletFactory->categoryServ()->getCategoryListByPaginate()->toArray() ?? [];
        $categoryList = assertTreeDatum($categoryList);
        $category2levelList = $this->filterCategoryItem($this->getAssertTreeDatum($categoryList));
        return renderResponse($category2levelList);
    }

    /**
     * getAssertTreeDatum
     * @param array $category2levelList
     * @return array
     */
    public function getAssertTreeDatum(array $category2levelList)
    {
        $category2levelListArr = [];
        foreach ($category2levelList as $categoryItem) {
            $category2levelListArr[] = $categoryItem;

            if (isset($categoryItem["categories"])) {
                $category2levelListArr = array_merge($category2levelListArr, $this->getAssertTreeDatum($categoryItem["categories"]));
            }
        }

        return $category2levelListArr;
    }

    /**
     * filterCategoryItem
     * @param $category2levelListArr
     * @return mixed
     */
    protected function filterCategoryItem($category2levelListArr)
    {
        return array_map(function ($categoryItem) {
            if (isset($categoryItem["categories"]))
                unset($categoryItem["categories"]);

            if ($categoryItem["parentID"] > 0)
                $categoryItem["categoryName"] = "|-- " . $categoryItem["categoryName"];

            return $categoryItem;
            
        }, $category2levelListArr);
    }

    /**
     * addGoodsCategory
     * @param array $categoryInfo
     * @return \think\response\Json
     */
    public function addGoodsCategory(array $categoryInfo)
    {
        $this->servletFactory->categoryServ()->createNewGoodsCategory($categoryInfo);
        return renderResponse();
    }

    /**
     * getCategoryDetailByCategoryID
     * @param int $categoryID
     * @return \think\response\Json
     */
    public function getCategoryDetailByCategoryID(int $categoryID)
    {
        $categoryDetail = $this->servletFactory->categoryServ()->getCategoryDetailByCategoryID($categoryID);
        return renderResponse($categoryDetail);
    }

    /**
     * editGoodsCategory
     * @param int $categoryID
     * @param array $category
     */
    public function editGoodsCategory(int $categoryID, array $category)
    {
        $categoryDetail = $this->servletFactory->categoryServ()->getCategoryDetailByCategoryID($categoryID);

        if ($categoryDetail->parentID > 0 && ($category["parentID"] == 0)) {
            throw new ParameterException(["errMessage" => "分类不能从二级变成一级..."]);
        }

        if ($categoryDetail->parentID == 0) {
            $category["parentID"] = 0;
        }
        $categoryDetail->allowField(["categoryName", "parentID", "categoryImgUrl", "sort", "status"])->save($category);

        return renderResponse();
    }

    /**
     * deleteGoodsCategoryByID
     * @param int $categoryID
     * @return \think\response\Json
     */
    public function deleteGoodsCategoryByID(int $categoryID)
    {
        $categoryDetail = $this->servletFactory->categoryServ()->getCategoryDetailByCategoryID($categoryID);

        if ($categoryDetail->parentID > 0 && !$categoryDetail->goods->isEmpty()) {
            throw new ParameterException(["errMessage" => "该分类下存在商品，不能删除..."]);
        }

        if (($categoryDetail->parentID == 0) && !$categoryDetail->categories->isEmpty()) {
            throw new ParameterException(["errMessage" => "该分类下存在子分类，不能删除..."]);
        }

        $categoryDetail->delete();

        return renderResponse();
    }
}