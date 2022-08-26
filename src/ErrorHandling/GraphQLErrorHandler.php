<?php declare(strict_types=1);

namespace HJerichen\FrameworkGraphQL\ErrorHandling;

use GraphQL\Error\Error;

interface GraphQLErrorHandler
{
    /** @param Error[] $errors */
    public function handleErrors(array $errors): array;
}
