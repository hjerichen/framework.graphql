<?php

namespace HJerichen\FrameworkGraphQL\Test\Integration;

use HJerichen\Framework\Application;
use HJerichen\Framework\Configuration\Configuration;
use HJerichen\FrameworkGraphQL\GraphQLRoute;
use HJerichen\FrameworkGraphQL\Test\Library\GraphQLTestInput;
use HJerichen\FrameworkGraphQL\Test\Library\GraphQLTestIO;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class ApplicationTest extends TestCase
{
    use ProphecyTrait;

    private Application $application;
    private GraphQLTestIO $ioDevice;
    private ObjectProphecy|Configuration $configuration;

    public function setUp(): void
    {
        parent::setUp();

        $this->ioDevice = new GraphQLTestIO();
        $this->configuration = $this->prophesize(Configuration::class);
        $this->setUpConfiguration();

        $this->application = new Application($this->ioDevice, $this->configuration->reveal());
        $this->application->addRoute(new GraphQLRoute());
    }

    public function testCallingQuery(): void
    {
        $input = new GraphQLTestInput(__DIR__ . '/query.gql');
        $this->ioDevice->setGraphQLInput($input);
        $this->ioDevice->setExpectedGraphQLResponse([
            'data' => [
                'user' => [
                    'id' => '1',
                    'name' => 'jon doe'
                ]
            ]
        ]);
        $this->application->execute();
    }

    public function testForGraphQLException(): void
    {
        $input = new GraphQLTestInput(__DIR__ . '/graphqlexception.gql');
        $this->ioDevice->setGraphQLInput($input);
        $this->ioDevice->setExpectedGraphQLResponse([
            'errors' => [
                [
                    'message' => 'test',
                    'extensions' => [
                        'category' => 'Exception'
                    ],
                    'locations' => [
                        [
                            'line' => 2,
                            'column' => 5
                        ]
                    ],
                    'path' => [
                        'graphqlexception'
                    ],
                ]
            ]
        ]);
        $this->application->execute();
    }

    public function testForValidationException(): void
    {
        $input = new GraphQLTestInput(__DIR__ . '/validationexception.gql');
        $this->ioDevice->setGraphQLInput($input);
        $this->ioDevice->setExpectedGraphQLResponse([
            'errors' => [
                [
                    'message' => 'not valid',
                    'extensions' => [
                        'category' => 'Validation'
                    ],
                    'locations' => [
                        [
                            'line' => 2,
                            'column' => 5
                        ]
                    ],
                    'path' => [
                        'validationexception'
                    ],
                ]
            ]
        ]);
        $this->application->execute();
    }

    public function testForException(): void
    {
        $input = new GraphQLTestInput(__DIR__ . '/exception.gql');
        $this->ioDevice->setGraphQLInput($input);
        $this->ioDevice->setExpectedGraphQLResponse([
            'errors' => [
                [
                    'message' => 'Internal server error',
                    'debugMessage' => 'test',
                    'extensions' => [
                        'category' => 'internal'
                    ],
                    'locations' => [
                        [
                            'line' => 2,
                            'column' => 5
                        ]
                    ],
                    'path' => [
                        'exception'
                    ],
                ]
            ]
        ]);
        $this->application->execute();
    }

    /* HELPERS */

    private function setUpConfiguration(): void
    {
        $this->configuration
            ->getCustomValue('graphqlite-namespace-types')
            ->willReturn('HJerichen\FrameworkGraphQL\Test\Helpers\Types');
        $this->configuration
            ->getCustomValue('graphqlite-namespace-controllers')
            ->willReturn('HJerichen\FrameworkGraphQL\Test\Helpers\Controllers');
        $this->configuration
            ->getCustomValue('graphqlite-error-handler')
            ->willReturn(null);
    }
}