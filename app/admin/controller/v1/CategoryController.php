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

}