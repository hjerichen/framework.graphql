<?php declare(strict_types=1);

namespace HJerichen\FrameworkGraphQL\Test\Helpers;

use HJerichen\FrameworkGraphQL\ErrorHandling\GraphQLErrorHandler;
use Override;

class ErrorHandler implements GraphQLErrorHandler
{
    #[Override]
    public function handleErrors(array $errors): array
    {
        return [];
    }
}
