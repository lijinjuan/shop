<?php

namespace app\admin\controller\v1;

use app\admin\repositories\AboutUsRepositories;
use think\facade\Cache;
use think\Request;

class AboutUsController
{
    /**
     * @var AboutUsRepositories
     */
    protected AboutUsRepositories $aboutUsRepositories;

    /**
     * @param AboutUsRepositories $aboutUsRepositories
     */
    public function __construct(AboutUsRepositories $aboutUsRepositories)
    {
        $this->aboutUsRepositories = $aboutUsRepositories;
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function addAboutUs(Request $request)
    {
        $content = $request->post('content');
        Cache::set("relationShip", $content);
        return renderResponse();
    }


    /**
     * @return \think\response\Json
     */
    public function getAboutUs()
    {
        $content = Cache::get("relationShip");
        return renderResponse(compact('content'));
    }

}