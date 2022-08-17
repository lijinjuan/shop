<?php

namespace app\agents\controller\v1;

use think\facade\Snowflake;
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
        return Snowflake::generate();
    }
}