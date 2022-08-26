<?php declare(strict_types=1);

namespace HJerichen\FrameworkGraphQL\ErrorHandling;

use GraphQL\Error\Debug;
use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;

class GraphQLErrorHandlerDefault implements GraphQLErrorHandler
{
    /** @param Error[] $errors */
    public function handleErrors(array $errors): array {
        return array_map([$this, 'formatError'], $errors);
    }

    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    protected function formatError(Error $error): array
    {
        $formatted = FormattedError::createFromException($error);
        $formatted = FormattedError::addDebugEntries($formatted, $error, Debug::INCLUDE_DEBUG_MESSAGE);
        return $formatted;
    }
}
