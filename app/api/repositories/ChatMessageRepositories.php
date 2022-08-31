<?php

namespace app\api\repositories;

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

}