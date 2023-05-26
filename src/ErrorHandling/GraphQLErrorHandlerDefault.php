<?php declare(strict_types=1);

namespace HJerichen\FrameworkGraphQL\ErrorHandling;

use GraphQL\Error\DebugFlag;
use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;

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
        $formatted = $this->appendDebugMessage($error, $formatted);
        $formatted = $this->appendCategory($error, $formatted);
        return $formatted;
    }

    private function appendDebugMessage(Error $error, array $formatted): array
    {
        $formatted = FormattedError::addDebugEntries($formatted, $error, DebugFlag::INCLUDE_DEBUG_MESSAGE);
        if (isset($formatted['extensions']['debugMessage'])) {
            $formatted['debugMessage'] = $formatted['extensions']['debugMessage'];
            unset($formatted['extensions']['debugMessage']);
        }
        return $formatted;
    }

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
