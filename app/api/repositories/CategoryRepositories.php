<?php

namespace app\api\repositories;

/**
 * \app\api\repositories\CategoryRepositories
 */
class CategoryRepositories extends AbstractRepositories
{
    /**
     * getCategoriesByAssert
     * @return \think\response\Json
     */
    public function getCategoriesByAssert()
    {
        $categories = $this->servletFactory->categoryServ()->getCategoriesByTree();
        return renderResponse($categories);
    }

    /**
     * getParentCategories
     * @return \think\response\Json
     */
    public function getParentCategories()
    {
        $categories = $this->servletFactory->categoryServ()->getParentCategories(0);
        return renderResponse($categories);
    }
}