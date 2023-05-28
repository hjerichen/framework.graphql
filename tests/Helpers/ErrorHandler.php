<?php declare(strict_types=1);

namespace HJerichen\FrameworkGraphQL\Test\Helpers;

use HJerichen\FrameworkGraphQL\ErrorHandling\GraphQLErrorHandler;

class ErrorHandler implements GraphQLErrorHandler
{
    public function handleErrors(array $errors): array
    {
        return [];
    }
}
