<?php

namespace HJerichen\FrameworkGraphQL;

use GraphQL\Error\Debug;
use GraphQL\GraphQL;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\JsonResponse;
use HJerichen\Framework\Response\Response;
use TheCodingMachine\GraphQLite\Context\Context;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class GraphQLInitiator
{

    public function __construct(
        private GraphQLSchemaFactory $schemaFactory
    ) {
    }

    public function execute(Request $request): Response
    {
        $schema = $this->schemaFactory->createSchema();
        $input = json_decode($request->getBody(), true, 512, JSON_THROW_ON_ERROR);

        $query = $input['query'];
        $variableValues = $input['variables'] ?? null;

        $debug = Debug::INCLUDE_DEBUG_MESSAGE;
        $result = GraphQL::executeQuery($schema, $query, null, new Context(), $variableValues);
        $output = $result->toArray($debug);
        return new JsonResponse(json_encode($output, JSON_THROW_ON_ERROR));
    }

//    private function test(): void
//    {
//        $schema = $this->schemaFactory->createSchema();
//
//
//        $rawInput = file_get_contents('php://input');
//        try {
//            $input = json_decode($rawInput, true, 512, JSON_THROW_ON_ERROR);
//            $query = $input['query'];
//            $variableValues = $input['variables'] ?? null;
//
//            $debug = Debug::INCLUDE_DEBUG_MESSAGE | Debug::RETHROW_INTERNAL_EXCEPTIONS | Debug::INCLUDE_TRACE;
//            $result = GraphQL::executeQuery($schema, $query, null, new Context(), $variableValues);
//            $output = $result->toArray($debug);
//            return new JsonResponse(json_encode($output));
//        } catch (\Throwable $throwable) {
//            $error = [
//                'errors' => [
//                    ['message' => (string)$throwable]
//                ]
//            ];
//            return new JsonResponse(json_encode($error, JSON_THROW_ON_ERROR));
//        }
//    }
}