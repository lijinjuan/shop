<?php

namespace app\admin\servlet;

use app\common\model\ImagesModel;

class ImagesServlet
{
    /**
     * @var ImagesModel
     */
    protected ImagesModel $imagesModel;

    /**
     * @param ImagesModel $imagesModel
     */
    public function __construct(ImagesModel $imagesModel)
    {
        $this->imagesModel = $imagesModel;
    }

    /**
     * @param $data
     * @return ImagesModel|\think\Model
     */
    public function addImage($data)
    {
        return $this->imagesModel::create($data);
    }


}