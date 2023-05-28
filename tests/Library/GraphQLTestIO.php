<?php

namespace HJerichen\FrameworkGraphQL\Test\Library;

use HJerichen\Framework\IODevice\IODevice;
use HJerichen\Framework\Request\Request;
use HJerichen\Framework\Response\Response;
use PHPUnit\Framework\TestCase;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class GraphQLTestIO implements IODevice
{
    private GraphQLTestInput $graphQLInput;
    private Request $request;
    private array $expectedGraphQLResponse;

    public function setGraphQLInput(GraphQLTestInput $graphQLInput): void
    {
        $this->graphQLInput = $graphQLInput;
    }

    public function setExpectedGraphQLResponse(array $expectedGraphQLResponse): void
    {
        $this->expectedGraphQLResponse = $expectedGraphQLResponse;
    }

    public function getRequest(): Request
    {
        return $this->request ?? $this->buildRequest();
    }

    public function outputResponse(Response $response): void
    {
        TestCase::assertJson($response->getContent());

        /** @var array $actualGraphQLResponse */
        $actualGraphQLResponse = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if ($this->hasNotExpectedError($actualGraphQLResponse)) {
            $message = 'Unexpected error in response: ' . print_r($actualGraphQLResponse, true);
            TestCase::assertEquals($this->expectedGraphQLResponse, $actualGraphQLResponse, $message);
        } else {
            TestCase::assertEquals($this->expectedGraphQLResponse, $actualGraphQLResponse);
        }
    }

    private function buildRequest(): Request
    {
        $requestBody = [
            'operationName' => 'awd',
            'variables' => $this->graphQLInput->variables,
            'query' => file_get_contents($this->graphQLInput->graphQLFile)
        ];
        $requestBodyAsString =  json_encode($requestBody, JSON_THROW_ON_ERROR);


        $this->request = new Request('/graphql', $requestBodyAsString);
        return $this->request;
    }

    private function hasNotExpectedError(array $actualGraphQLResponse): bool
    {
        return
            array_key_exists('data', $this->expectedGraphQLResponse) &&
            array_key_exists('errors', $actualGraphQLResponse);
    }
}