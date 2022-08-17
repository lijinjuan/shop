<?php

namespace app\agents\controller\v1;

use think\Request;

/**
 * \app\agents\controller\v1\EntryController
 */
class EntryController
{

    /**
     * userLaunch
     * @param \think\Request $request
     * @return string
     */
    public function userLaunch(Request $request)
    {
        return __CLASS__ . "/" . __FUNCTION__;
    }
}