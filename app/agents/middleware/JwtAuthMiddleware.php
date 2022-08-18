<?php

namespace app\agents\middleware;

use app\agents\servlet\AgentsServlet;
use app\lib\exception\TokenInvalidException;
use thans\jwt\exception\TokenBlacklistGracePeriodException;
use thans\jwt\exception\TokenExpiredException;
use thans\jwt\middleware\BaseMiddleware;

/**
 * \app\api\middleware\JwtAuthMiddleware
 */
class JwtAuthMiddleware extends BaseMiddleware
{
    /**
     * handle
     * @param $request
     * @param \Closure $next
     */
    public function handle($request, \Closure $next)
    {
        // OPTIONS请求直接返回
        if ($request->isOptions()) {
            return response();
        }

        // 验证token
        try {
            $payload = $this->auth->auth();
            $this->bindUser2Container($payload);
            return $next($request);
        } catch (TokenExpiredException) {
            // 尝试刷新token
            try {
                $this->auth->setRefresh();
                $token = $this->auth->refresh();

                $payload = $this->auth->auth(false);
                $this->bindUser2Container($payload);

                $response = $next($request);
                return $this->setAuthentication($response, $token);
            } catch (TokenBlacklistGracePeriodException) {
                goto GRACE_PERIOD_EXCEPTION;
            }
        } catch (TokenBlacklistGracePeriodException) {
            goto GRACE_PERIOD_EXCEPTION;
        } catch (\Throwable) {
            throw new TokenInvalidException();
        }

        GRACE_PERIOD_EXCEPTION:
        $payload = $this->auth->auth(false);
        $this->bindUser2Container($payload);
        return $next($request);
    }

    /**
     *  绑定用户到容器 bind user to container
     * getParse2Payload
     * @param array $payload
     */
    protected function bindUser2Container(array $payload): void
    {
        $agentID = (int)$payload['agentID']->getValue();
        $agentModel = invoke(AgentsServlet::class)->getAgentsProfileByFields(["id" => $agentID]);

        app()->bind("agentProfile", $agentModel);
    }
}