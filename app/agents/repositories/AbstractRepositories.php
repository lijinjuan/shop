<?php

namespace app\agents\repositories;

use app\agents\servlet\contract\ServletFactoryInterface;

/**
 * \app\agents\repositories\AbstractRepositories
 */
abstract class AbstractRepositories
{

    /**
     * @var \app\agents\servlet\contract\ServletFactoryInterface
     */
    protected ServletFactoryInterface $servletFactory;

    /**
     * @param \app\agents\servlet\contract\ServletFactoryInterface $servletFactory
     */
    public function __construct(ServletFactoryInterface $servletFactory)
    {
        $this->servletFactory = $servletFactory;
    }

}