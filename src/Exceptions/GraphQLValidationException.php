<?php

namespace HJerichen\FrameworkGraphQL\Exceptions;

use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class GraphQLValidationException extends GraphQLException
{

    public function getCategory(): string
    {
        return 'Validation';
    }
}