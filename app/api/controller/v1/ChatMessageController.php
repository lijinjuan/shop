<?php

namespace app\api\controller\v1;

use app\api\repositories\ChatMessageRepositories;
use think\Request;


class ChatMessageController
{

    protected ChatMessageRepositories $chatMessageRepositories;

    /**
     * @param ChatMessageRepositories $chatMessageRepositories
     */
    public function __construct(ChatMessageRepositories $chatMessageRepositories)
    {
        $this->chatMessageRepositories = $chatMessageRepositories;
    }


    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \app\lib\exception\ParameterException
     */
    public function sendMessage(Request $request)
    {
        $message = $request->post(['fromUserID', 'fromUserAvatar', 'fromUserName', 'fromUserRoleID', 'toUserID', 'toUserAvatar', 'toUserName', 'toUserRoleID', 'messageBody', 'messageType']);
        $this->chatMessageRepositories->addChatMessage(array_filter($message));
        $this->chatMessageRepositories->sendMessage($message);
        return renderResponse();
    }


    /**
     * @param int $userID
     * @return \think\response\Json
     */
    public function getMessageCountByUserID(int $userID)
    {
        return renderResponse($this->chatMessageRepositories->getMessageCountByUserID($userID));
    }

    /**
     * @param int $userID
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLastMessageByUserID(int $userID)
    {
        return renderResponse($this->chatMessageRepositories->getLastChatMessage($userID));
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     */
    public function setRead(Request $request)
    {
        $fromUserID = $request->post('fromUserID');
        $toUserID = $request->post('toUserID');
        $this->chatMessageRepositories->setRead($fromUserID, $toUserID);
        return renderResponse();
    }

    /**
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getMessageListByID(Request $request)
    {
        $fromUserID = $request->post('fromUserID');
        $toUserID = $request->post('toUserID');
        $pageSize = $request->post('pageSize',20);
        return $this->chatMessageRepositories->getMessageList($fromUserID,$toUserID,$pageSize);
    }

}