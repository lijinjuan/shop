<?php

namespace app\store\repositories;

use app\store\servlet\contract\ServletFactoryInterface;

/**
 * \app\store\repositories\AbstractRepositories
 */
abstract class AbstractRepositories
{

    /**
     * @var \app\store\servlet\contract\ServletFactoryInterface
     */
    protected ServletFactoryInterface $servletFactory;

    /**
     * @param \app\store\servlet\contract\ServletFactoryInterface $servletFactory
     */
    public function __construct(ServletFactoryInterface $servletFactory)
    {
        $this->servletFactory = $servletFactory;
    }

}