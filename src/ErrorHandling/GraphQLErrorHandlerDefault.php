<?php
/** @noinspection PhpDocMissingThrowsInspection */
declare(strict_types=1);

namespace HJerichen\FrameworkGraphQL\ErrorHandling;

use GraphQL\Error\DebugFlag;
use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use HJerichen\FrameworkGraphQL\Exceptions\GraphQLValidationException;
use Override;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;

/**
 * @psalm-type SerializableError array{
 *   message: string,
 *   locations?: array<int, array{line: int, column: int}>,
 *   path?: array<int, int|string>,
 *   extensions?: array<string, mixed>
 * }
 */
class GraphQLErrorHandlerDefault implements GraphQLErrorHandler
{
    /** @param Error[] $errors */
    #[Override]
    public function handleErrors(array $errors): array {
        return array_map([$this, 'formatError'], $errors);
    }

    /** @return SerializableError */
    protected function formatError(Error $error): array
    {
        $formatted = FormattedError::createFromException($error);
        $formatted = FormattedError::addDebugEntries($formatted, $error, DebugFlag::INCLUDE_DEBUG_MESSAGE);
        return $this->appendCategory($error, $formatted);
    }

    /**
     * @param SerializableError $formatted
     * @return SerializableError
     */
    private function appendCategory(Error $error, array $formatted): array
    {
        $exception = $error->getPrevious();
        if ($exception instanceof GraphQLValidationException) {
            $formatted['extensions']['category'] = 'Validation';
        } else if ($exception instanceof GraphQLException) {
            $formatted['extensions']['category'] = 'Exception';
        } else {
            $formatted['extensions']['category'] = 'Internal';
        }
        return $formatted;
    }
}
