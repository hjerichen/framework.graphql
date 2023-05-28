<?php
/** @noinspection PhpDocMissingThrowsInspection */
declare(strict_types=1);

namespace HJerichen\FrameworkGraphQL\ErrorHandling;

use GraphQL\Error\DebugFlag;
use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
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
    public function handleErrors(array $errors): array {
        return array_map([$this, 'formatError'], $errors);
    }

    /** @return SerializableError */
    protected function formatError(Error $error): array
    {
        $formatted = FormattedError::createFromException($error);
        /** @var SerializableError $formatted */
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
        if ($exception instanceof GraphQLException) {
            $formatted['extensions']['category'] = $exception->getCategory();
        } else {
            $formatted['extensions']['category'] = 'internal';
        }
        return $formatted;
    }
}
