<?php

namespace app\api\middleware;

use app\api\servlet\UsersServlet;
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
        $userID = (int)$payload['userID']->getValue();
        $userModel = invoke(UsersServlet::class)->getUserProfileByFields(["id" => $userID]);

        app()->bind("userProfile", $userModel);
    }
}