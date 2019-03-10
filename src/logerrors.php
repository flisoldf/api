<?php
/**
 * Display errors.
 */
ini_set('display_errors', false);
ini_set('html_errors', 0);

/**
 * Error reporting.
 */
error_reporting(-1);

if (!function_exists('ShutdownHandler')) {
    
    /**
     * Shutdown handler.
     *
     * @return boolean
     */
    function ShutdownHandler()
    {
        if (@is_array($error = @error_get_last())) {
            return (@call_user_func_array('ErrorHandler', $error));
        }

        return true;
    }

    register_shutdown_function('ShutdownHandler');
}

if (!function_exists('ErrorHandler')) {

    /**
     * Error handler.
     *
     * @param $type
     * @param $message
     * @param $file
     * @param $line
     * @return bool
     * @throws Exception
     */
    function ErrorHandler($type, $message, $file, $line)
    {
        $_ERRORS = Array(
            0x0001 => 'E_ERROR',
            0x0002 => 'E_WARNING',
            0x0004 => 'E_PARSE',
            0x0008 => 'E_NOTICE',
            0x0010 => 'E_CORE_ERROR',
            0x0020 => 'E_CORE_WARNING',
            0x0040 => 'E_COMPILE_ERROR',
            0x0080 => 'E_COMPILE_WARNING',
            0x0100 => 'E_USER_ERROR',
            0x0200 => 'E_USER_WARNING',
            0x0400 => 'E_USER_NOTICE',
            0x0800 => 'E_STRICT',
            0x1000 => 'E_RECOVERABLE_ERROR',
            0x2000 => 'E_DEPRECATED',
            0x4000 => 'E_USER_DEPRECATED'
        );

        if (!@is_string($name = @array_search($type, @array_flip($_ERRORS)))) {
            $name = 'E_UNKNOWN';
        }

        $handle = fopen(__DIR__ . '/../logs/errors.log', 'a') or die('Unable to open file!');
        fwrite($handle, @sprintf("%s %s Error in file \"%s\" at line %d: %s\n", (new DateTime())->format('Y-m-d H:i:s'), $name, $file, $line, $message));
        fclose($handle);

        return true;
    }

    $old_error_handler = set_error_handler('ErrorHandler');
}
