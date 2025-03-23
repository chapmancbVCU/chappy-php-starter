<?php
namespace Core;

use Whoops\Run;
use Core\Lib\Utilities\ArraySet;
use Core\Lib\Logging\Logger;
use Whoops\Handler\PrettyPageHandler;

/**
 * Performs error handling for application.
 */
class ErrorHandler {
    /**
     * Performs global exception handling, global error handling, and 
     * initialized whoops.
     *
     * @return void
     */
    public static function initialize(): void {
        // Global Exception Handler
        set_exception_handler(function ($exception) {
            Logger::log("Uncaught Exception: {$exception->getMessage()} | File: {$exception->getFile()} | Line: {$exception->getLine()}", 'error');
        });

        // Global Error Handler
        set_error_handler(function ($severity, $message, $file, $line) {
            Logger::log("Fatal Error: [$severity] $message | File: $file | Line: $line", 'error');
        });

        // Shutdown Handler for Fatal Errors
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error) {
                // Wrap the error array in Arr
                $errorData = ArraySet::make($error);
                
                // Check if error type is in the list of fatal errors
                if ($errorData->hasAny([E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR])->result()) {
                    Logger::log(
                        "Fatal Shutdown Error: {$errorData->get('message')->result()} | File: {$errorData->get('file')->result()} | Line: {$errorData->get('line')->result()}",
                        'critical'
                    );
                }
            }
        });

        // Initialize whoops
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
    }
}
