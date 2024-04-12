<?php

namespace SlimSecure\Core;

use SlimSecure\Configs\Env;

/**
 * Class Exceptions
 *
 * Handles error and exception management for the Slimez project. This class converts
 * standard PHP errors into exceptions and provides a centralized exception handling
 * mechanism that supports both displaying errors directly to users during development
 * and logging errors for production environments.
 */
class Exceptions
{
    /**
     * @var resource|null A file resource handle to the error log file, used for writing logs.
     */
    protected static $fileHandle;

    /**
     * Error handler method that converts errors to ErrorException.
     *
     * This method acts as a custom error handler, converting all errors (warnings, notices, etc.)
     * to ErrorException, which can then be caught by the exception handling mechanism. It respects
     * the error_reporting level to ensure compatibility with the @ operator.
     *
     * @param int $level The level of the error raised.
     * @param string $message The error message.
     * @param string $file The filename where the error was raised.
     * @param int $line The line number in the file where the error was raised.
     *
     * @throws \ErrorException Throws an ErrorException if error_reporting is not set to 0.
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {  // Ensures that error suppression with @ operator is respected
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Centralized exception handler.
     *
     * This method manages uncaught exceptions by either displaying them directly to the user or
     * logging them to a file depending on the application's environment settings. It sets the
     * appropriate HTTP response code based on the exception type.
     *
     * @param \Exception $exception The caught exception.
     *
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        // Determine the HTTP response code based on the exception code
        $code = $exception->getCode();
        $code = ($code != 404) ? 500 : $code;
        http_response_code($code);

        if (Env::SHOW_ERRORS) {
            // Display detailed error page if SHOW_ERRORS is enabled
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        } else {
            // Log the error to a file if SHOW_ERRORS is disabled
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();

            error_log($message);
        }
    }
}
