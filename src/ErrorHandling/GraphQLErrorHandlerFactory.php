<?php declare(strict_types=1);

namespace HJerichen\FrameworkGraphQL\ErrorHandling;

use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\ObjectFactory;
use RuntimeException;

class GraphQLErrorHandlerFactory
{
    public function __construct(
        private Configuration $configuration,
        private ObjectFactory $objectFactory
    ) {
    }

    public function createErrorHandler(): GraphQLErrorHandler
    {
        $handler = $this->objectFactory->instantiateClass($this->getHandlerClass());
        if ($handler instanceof GraphQLErrorHandler) return $handler;
        throw new RuntimeException("Wrong graphql exception handler class");
    }

    private function getHandlerClass(): string
    {
        $handlerClass = $this->configuration->getCustomValue('graphqlite-error-handler');
        return $handlerClass ?? GraphQLErrorHandlerDefault::class;
    }
}
