<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * \app\common\model\ImagesModel
 */
class ImagesModel extends Model
{
    /**
     * @var string
     */
    protected $table = "s_images";

    /**
     * @var string
     */
    protected $createTime = "createdAt";

    /**
     * @var string
     */
    protected $updateTime = "updatedAt";

    /**
     * @var bool
     */
    protected $autoWriteTimestamp = true;

    /**
     * @var string[]
     */
    protected $hidden = ['id', 'imgID', 'bannerID', 'createdAt', 'updatedAt', 'deletedAt'];
}