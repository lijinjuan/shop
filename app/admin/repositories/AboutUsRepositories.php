<?php

namespace app\admin\repositories;

class AboutUsRepositories extends AbstractRepositories
{
    /**
     * @param array $data
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function addAboutUs(array $data)
    {
        return renderResponse($this->servletFactory->aboutUsServ()->addAboutUs($data));
    }

}