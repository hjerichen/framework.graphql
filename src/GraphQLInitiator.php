<?php

namespace HJerichen\FrameworkGraphQL;

use GraphQL\GraphQL;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\JsonResponse;
use HJerichen\Framework\Response\Response;
use HJerichen\FrameworkGraphQL\ErrorHandling\GraphQLErrorHandler;
use HJerichen\FrameworkGraphQL\ErrorHandling\GraphQLErrorHandlerFactory;
use TheCodingMachine\GraphQLite\Context\Context;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class GraphQLInitiator
{

    public function __construct(
        private readonly GraphQLErrorHandlerFactory $errorHandlerFactory,
        private readonly GraphQLSchemaFactory $schemaFactory,
    ) {
    }

    public function execute(Request $request): Response
    {
        $schema = $this->schemaFactory->createSchema();
        $input = json_decode($request->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $query = $input['query'];
        $variableValues = $input['variables'] ?? null;

        $result = GraphQL::executeQuery($schema, $query, null, new Context(), $variableValues);
        $result->setErrorsHandler([$this->createErrorHandler(), 'handleErrors']);

        $output = $result->toArray();
        return new JsonResponse(json_encode($output, JSON_THROW_ON_ERROR));
    }

    private function createErrorHandler(): GraphQLErrorHandler
    {
        return $this->errorHandlerFactory->createErrorHandler();
    }
}