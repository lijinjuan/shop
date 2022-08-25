<?php

namespace app\admin\servlet;

use app\common\model\MessagesModel;
use app\lib\exception\ParameterException;

class MessageServlet
{
    /**
     * @var MessagesModel
     */
    protected MessagesModel $messagesModel;

    /**
     * @param MessagesModel $messagesModel
     */
    public function __construct(MessagesModel $messagesModel)
    {
        $this->messagesModel = $messagesModel;
    }


    /**
     * @param array $data
     * @return MessagesModel|\think\Model
     * @throws ParameterException
     */
    public function addMessage(array $data)
    {
        try {
            return $this->messagesModel::create($data);
        }catch (\Throwable $e){
            throw new ParameterException(['errMessage'=>'站内信写入失败...'.$e->getMessage()]);
        }
    }
}