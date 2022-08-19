<?php

namespace app\admin\repositories;

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
}