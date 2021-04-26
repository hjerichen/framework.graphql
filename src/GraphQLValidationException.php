<?php

namespace HJerichen\FrameworkGraphQL;

use Exception;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLExceptionInterface;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class GraphQLValidationException extends Exception implements GraphQLExceptionInterface
{

    public function isClientSafe(): bool
    {
        return true;
    }

    public function getCategory(): string
    {
        return 'Validation';
    }

    public function getExtensions(): array
    {
        return [];
    }
}