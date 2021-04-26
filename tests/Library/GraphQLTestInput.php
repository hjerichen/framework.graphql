<?php

namespace HJerichen\FrameworkGraphQL\Test\Library;

/**
 * @author Heiko Jerichen <heiko@jerichen.de>
 */
class GraphQLTestInput
{
    public function __construct(
        public string $graphQLFile,
        public array $variables = [],
    ) {
    }
}