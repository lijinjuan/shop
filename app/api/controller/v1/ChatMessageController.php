<?php

namespace app\api\controller\v1;

use app\api\repositories\ChatMessageRepositories;
use think\Request;


class ChatMessageController
{

    protected ChatMessageRepositories $chatMessageRepositories;
    /**
     * @param Request $request
     * @return void
     */
    public function isOnline(Request $request)
    {
        $userID = $request->get('id');

    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function sendMessage(Request $request)
    {
        $message = $request->input(['fromUserID','fromUserAvatar','fromUserName','toUserID','toUserAvatar','toUserName','messageBody','isRead']);
        $this->chatMessageRepositories->addChatMessage(array_filter($message));
        $isOnline = 1;
//        if ($isOnline){
//            //请求老梁接口转发消息
//
//        }
        return renderResponse();
    }

}