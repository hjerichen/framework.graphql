<?php

namespace HJerichen\FrameworkGraphQL\Test\Helpers\Types;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
#[Type]
class User
{
    #[Field(outputType: "ID!")]
    public function getId(): int
    {
        return 1;
    }

    #[Field]
    public function getName(): string
    {
        return 'jon doe';
    }
}