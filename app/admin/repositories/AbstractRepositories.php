<?php

namespace app\admin\repositories;

use app\admin\servlet\contract\ServletFactoryInterface;

/**
 * \app\admin\repositories\AbstractRepositories
 */
abstract class AbstractRepositories
{
    /**
     * @var \app\admin\servlet\contract\ServletFactoryInterface
     */
    protected ServletFactoryInterface $servletFactory;

    /**
     * @param \app\admin\servlet\contract\ServletFactoryInterface $servletFactory
     */
    public function __construct(ServletFactoryInterface $servletFactory)
    {
        $this->servletFactory = $servletFactory;
    }

}