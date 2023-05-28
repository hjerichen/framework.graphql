<?php declare(strict_types=1);

namespace HJerichen\FrameworkGraphQL\Test\Unit\ErrorHandling;

use HJerichen\Framework\Configuration\Configuration;
use HJerichen\Framework\ObjectFactory;
use HJerichen\FrameworkGraphQL\ErrorHandling\GraphQLErrorHandler;
use HJerichen\FrameworkGraphQL\ErrorHandling\GraphQLErrorHandlerDefault;
use HJerichen\FrameworkGraphQL\ErrorHandling\GraphQLErrorHandlerFactory;
use HJerichen\FrameworkGraphQL\Test\Helpers\ErrorHandler;
use HJerichen\FrameworkGraphQL\Test\Helpers\Types\User;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use RuntimeException;

class GraphQLErrorHandlerFactoryTest extends TestCase
{
    use ProphecyTrait;

    private GraphQLErrorHandlerFactory $factory;
    private ObjectProphecy $exceptionHandler;
    private ObjectProphecy $objectFactory;
    private ObjectProphecy $configuration;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exceptionHandler = $this->prophesize(GraphQLErrorHandler::class);
        $this->objectFactory = $this->prophesize(ObjectFactory::class);
        $this->configuration = $this->prophesize(Configuration::class);

        $this->factory = new GraphQLErrorHandlerFactory(
            $this->configuration->reveal(),
            $this->objectFactory->reveal(),
        );
    }

    public function test_createExceptionHandler_forNoConfiguration(): void
    {
        $this->configuration
            ->getCustomValue('graphqlite-error-handler')
            ->willReturn(null);
        $this->objectFactory
            ->instantiateClass(GraphQLErrorHandlerDefault::class)
            ->willReturn($this->exceptionHandler->reveal());

        $this->assertSame(
            expected: $this->exceptionHandler->reveal(),
            actual: $this->factory->createErrorHandler(),
        );
    }

    public function test_createExceptionHandler_forConfiguration(): void
    {
        $this->configuration
            ->getCustomValue('graphqlite-error-handler')
            ->willReturn(ErrorHandler::class);
        $this->objectFactory
            ->instantiateClass(ErrorHandler::class)
            ->willReturn($this->exceptionHandler->reveal());

        $this->assertSame(
            expected: $this->exceptionHandler->reveal(),
            actual: $this->factory->createErrorHandler(),
        );
    }

    public function test_createExceptionHandler_forConfigurationIsNotClass(): void
    {
        $this->configuration
            ->getCustomValue('graphqlite-error-handler')
            ->willReturn('SomeString');

        $expected = new RuntimeException("Wrong graphql exception handler class");
        $this->expectExceptionObject($expected);

        $this->factory->createErrorHandler();
    }

    public function test_createExceptionHandler_foConfigurationDoesNotImplementsHandler(): void
    {
        $this->configuration
            ->getCustomValue('graphqlite-error-handler')
            ->willReturn(User::class);
        $this->objectFactory
            ->instantiateClass(User::class)
            ->willReturn($this->factory);

        $expected = new RuntimeException("Wrong graphql exception handler class");
        $this->expectExceptionObject($expected);

        $this->factory->createErrorHandler();
    }
}
