<?php

namespace app\api\repositories;

use app\api\servlet\contract\ServletFactoryInterface;

/**
 * \app\api\repositories\AbstractRepositories
 */
abstract class AbstractRepositories
{

    /**
     * @var \app\api\servlet\contract\ServletFactoryInterface
     */
    protected ServletFactoryInterface $servletFactory;

    /**
     * @param \app\api\servlet\contract\ServletFactoryInterface $servletFactory
     */
    public function __construct(ServletFactoryInterface $servletFactory)
    {
        $this->servletFactory = $servletFactory;
    }

}