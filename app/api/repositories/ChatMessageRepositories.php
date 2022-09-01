<?php

namespace app\api\repositories;

use GuzzleHttp\Client;

class ChatMessageRepositories extends AbstractRepositories
{

    /**
     * @param array $data
     * @return \app\common\model\ChatMessageModel|\think\Model
     * @throws \app\lib\exception\ParameterException
     */
    public function addChatMessage(array $data)
    {
        return $this->servletFactory->chatMessageServ()->addChatMessage($data);
    }


    /**
     * @param array $messageData
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMessage(array $messageData)
    {
        $isOnline = $this->isOnline((int)$messageData['toUserID'], (int)$messageData['toUserRoleID']);
        if ($isOnline['errCode'] == 100000 && $isOnline['responseData']['online'] == true) {
            $this->send($messageData);
        }
        return true;
    }

    /**
     * @param int $uid
     * @param int $roleID
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function isOnline(int $uid, int $roleID)
    {
        $client = new Client([
            'header' => ['Content-Type' => 'application/json'],
            'base_uri' => REQUEST_URL,
            'timeout' => 2.0
        ]);
        $response = $client->request('POST', '/v1/users/is-online', [
            'json' => ['userID' => $uid, 'roleID' => $roleID]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function send(array $data)
    {
        $client = new Client([
            'header' => ['Content-Type' => 'application/json'],
            'base_uri' => REQUEST_URL,
            'timeout' => 2.0
        ]);
        $response = $client->request('POST', '/v1/users/send-message', [
            'json' => $data,
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getMessageCountByUserID(int $id)
    {
        return $this->servletFactory->chatMessageServ()->getMessageCountByUserID($id);

    }

    /**
     * @param int $toUserID
     * @return \app\common\model\ChatMessageModel|\app\common\model\ChatMessageModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLastChatMessage(int $toUserID)
    {
        $model = $this->servletFactory->chatMessageServ()->getLastConnect($toUserID);
        if ($model) {
            $data = $this->servletFactory->chatMessageServ()->getLastMessage($model->fromUserID, $toUserID, true);
        }
        return !empty($data) ? $data : [];
    }

    /**
     * no read message list
     * @param int $fromUserID
     * @param int $toUserID
     * @param int $pageSize
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getMessageList(int $fromUserID,int $toUserID,int $pageSize)
    {
        return renderPaginateResponse($this->servletFactory->chatMessageServ()->getMessageList($fromUserID,$toUserID,$pageSize));
    }

    /**
     * @param int $fromUserID
     * @param int $toUserID
     * @return \app\common\model\ChatMessageModel
     */
    public function setRead(int $fromUserID,int $toUserID)
    {
        return $this->servletFactory->chatMessageServ()->setRead($fromUserID,$toUserID);
    }


}