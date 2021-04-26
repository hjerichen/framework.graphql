<?php

namespace HJerichen\FrameworkGraphQL;

use Cache\Adapter\PHPArray\ArrayCachePool;
use HJerichen\ClassInstantiator\ClassInstantiatorContainer;
use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\ObjectFactory;
use RuntimeException;
use TheCodingMachine\GraphQLite\Schema;
use TheCodingMachine\GraphQLite\SchemaFactory;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class GraphQLSchemaFactory extends SchemaFactory
{
    public function __construct(
        private Configuration $configuration,
        ObjectFactory $objectFactory
    ) {
        parent::__construct(new ArrayCachePool(), new ClassInstantiatorContainer($objectFactory));
    }

    public function createSchema(): Schema
    {
        $this->addControllerNamespace($this->getControllerNamespace());
        $this->addTypeNamespace($this->getTypeNamespace());
        return parent::createSchema();
    }

    protected function getControllerNamespace(): string
    {
        $namespace = $this->configuration->getCustomValue('graphqlite-namespace-controllers');
        return $namespace ?? throw new RuntimeException('No namespace controllers given.');
    }

    protected function getTypeNamespace(): string
    {
        $namespace = $this->configuration->getCustomValue('graphqlite-namespace-types');
        return $namespace ?? throw new RuntimeException('No namespace types given.');
    }
}