<?php

namespace app\api\controller\v1;

use app\api\repositories\CategoryRepositories;

/**
 * \app\api\controller\v1\CategoryController
 */
class CategoryController
{
    /**
     * @var \app\api\repositories\CategoryRepositories
     */
    protected CategoryRepositories $categoryRepositories;

    /**
     * @param \app\api\repositories\CategoryRepositories $categoryRepositories
     */
    public function __construct(CategoryRepositories $categoryRepositories)
    {
        $this->categoryRepositories = $categoryRepositories;
    }

    /**
     * getCategoriesByAssert
     * @return \think\response\Json
     */
    public function getCategoriesByAssert()
    {
        return $this->categoryRepositories->getCategoriesByAssert();
    }

    /**
     * getParentCategories
     * @return \think\response\Json
     */
    public function getParentCategories()
    {
        return $this->categoryRepositories->getParentCategories();
    }

}