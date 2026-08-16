<?php

namespace HJerichen\FrameworkGraphQL;

use HJerichen\Framework\ObjectFactory;
use HJerichen\Framework\Route\RouteInterface;
use Override;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class GraphQLRoute implements RouteInterface
{

    #[Override]
    public function getUri(): string
    {
        return '/graphql';
    }

    #[Override]
    public function getInstantiatedClass(ObjectFactory $objectFactory): object
    {
        return $objectFactory->instantiateClass(GraphQLInitiator::class);
    }

    #[Override]
    public function getMethod(): string
    {
        return 'execute';
    }
}