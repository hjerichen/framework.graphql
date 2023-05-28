<?php declare(strict_types=1);

namespace HJerichen\FrameworkGraphQL\ErrorHandling;

use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\ObjectFactory;
use RuntimeException;

class GraphQLErrorHandlerFactory
{
    public function __construct(
        private readonly Configuration $configuration,
        private readonly ObjectFactory $objectFactory
    ) {
    }

    public function createErrorHandler(): GraphQLErrorHandler
    {
        $handler = $this->objectFactory->instantiateClass($this->getHandlerClass());
        if ($handler instanceof GraphQLErrorHandler) return $handler;
        throw new RuntimeException("Wrong graphql exception handler class");
    }

    /**
     * @return class-string
     * @noinspection PhpRedundantVariableDocTypeInspection
     */
    private function getHandlerClass(): string
    {
        /** @var mixed $handlerClass */
        $handlerClass = $this->configuration->getCustomValue('graphqlite-error-handler');
        if (!$handlerClass) return GraphQLErrorHandlerDefault::class;

        if (is_string($handlerClass) && class_exists($handlerClass)) return $handlerClass;
        throw new RuntimeException("Wrong graphql exception handler class");
    }
}
