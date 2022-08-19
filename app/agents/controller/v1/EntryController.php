<?php

namespace app\agents\controller\v1;


use app\agents\repositories\AgentsRepositories;
use app\common\service\InviteServiceInterface;
use app\lib\exception\ParameterException;
use think\captcha\facade\Captcha;
use think\Request;

/**
 * \app\agents\controller\v1\EntryController
 */
class EntryController
{

    /**
     * @var \app\agents\repositories\AgentsRepositories
     */
    protected AgentsRepositories $agentsRepositories;

    /**
     * @param \app\agents\repositories\AgentsRepositories $agentsRepositories
     */
    public function __construct(AgentsRepositories $agentsRepositories)
    {
        $this->agentsRepositories = $agentsRepositories;
    }

    /**
     * createCaptcha
     * @return \think\Response
     */
    public function createCaptcha()
    {
        return Captcha::create();
    }

    /**
     * userLaunch
     * @param \think\Request $request
     * @return \think\response\Json
     */
    public function userLaunch(Request $request)
    {
        $verifyCode = (string)$request->param("verifyCode", "");

//        if (!captcha_check($verifyCode)) {
//            throw new ParameterException(["errMessage" => "验证码错误或者失效..."]);
//        }

        $agentProfile = $request->only(["agentAccount", "agentPassword"]);
        $localIP = $request->ip();
        return $this->agentsRepositories->userLaunch2Agents($agentProfile, $localIP);
    }
}