<?php

namespace HJerichen\FrameworkGraphQL;

use Cache\Adapter\PHPArray\ArrayCachePool;
use HJerichen\ClassInstantiator\ClassInstantiatorContainer;
use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\ObjectFactory;
use Override;
use RuntimeException;
use TheCodingMachine\GraphQLite\Schema;
use TheCodingMachine\GraphQLite\SchemaFactory;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class GraphQLSchemaFactory extends SchemaFactory
{
    public function __construct(
        private readonly Configuration $configuration,
        ObjectFactory $objectFactory
    ) {
        parent::__construct(new ArrayCachePool(), new ClassInstantiatorContainer($objectFactory));
    }

    #[Override]
    public function createSchema(): Schema
    {
        $this->addNamespace($this->getNamespace());
        return parent::createSchema();
    }

    protected function getNamespace(): string
    {
        $namespace = $this->configuration->getCustomValue('graphqlite-namespace');
        if (!$namespace) throw new RuntimeException('No namespace for graphqlite given.');
        if (!is_string($namespace)) throw new RuntimeException('Namespace for graphqlite needs to be a string.');
        return $namespace;
    }
}