<?php /** @noinspection PhpUnused */

namespace HJerichen\FrameworkGraphQL\Test\Helpers\Controllers;

use Exception;
use HJerichen\FrameworkGraphQL\Exceptions\GraphQLValidationException;
use HJerichen\FrameworkGraphQL\Test\Helpers\Types\User;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 * @api
 */
class Controller
{
    #[Query]
    public function user(): User
    {
        return new User();
    }

    #[Query]
    public function graphqlexception(): User
    {
        throw new GraphQLException('test');
    }

    #[Query]
    public function exception(): User
    {
        /** @noinspection ThrowRawExceptionInspection */
        throw new Exception('test');
    }

    #[Query]
    public function validationexception(): User
    {
        throw new GraphQLValidationException('not valid');
    }
}