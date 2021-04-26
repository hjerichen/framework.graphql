<?php

namespace HJerichen\FrameworkGraphQL;

use HJerichen\Framework\ObjectFactory;
use HJerichen\Framework\Route\RouteInterface;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class GraphQLRoute implements RouteInterface
{

    public function getUri(): string
    {
        return '/graphql';
    }

    public function getInstantiatedClass(ObjectFactory $objectFactory): object
    {
        return $objectFactory->instantiateClass(GraphQLInitiator::class);
    }

    public function getMethod(): string
    {
        return 'execute';
    }
}