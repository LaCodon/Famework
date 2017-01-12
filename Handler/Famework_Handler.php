<?php

namespace Famework\Handler;

class Famework_Handler {

    public static function registerDefaultHandler() {
        set_error_handler(array(new Famework_Handler(), 'onError'));
        set_exception_handler(array(new Famework_Handler(), 'onException'));
    }

    /**
     * Error handler
     */
    public function onError($errno, $errstr, $errfile, $errline) {
        if (error_reporting() === (E_ALL | E_STRICT) || error_reporting() === (E_ALL)) {
            if ($errno === E_NOTICE || $errno === E_WARNING) {
                echo '<h1 style="color: orange;">Error!</h1>';
            } else {
                echo '<h1 style="color: red;">Error!</h1>';
            }
            echo '<table>';
            echo '<tr><td>File</td><td>&nbsp;&nbsp;&nbsp;&nbsp;Error</td></tr>';
            printf('<tr><td>%s:%s</td><td>&nbsp;&nbsp;&nbsp;&nbsp;%s</td></tr>', $errfile, $errline, $errstr);
            echo '</table>';
            print_r(error_get_last());
        }

        return TRUE;
    }

    /**
     * Exception handler
     */
    public function onException(\Exception $exception) {
        if (error_reporting() === (E_ALL | E_STRICT) || error_reporting() === (E_ALL)) {
            echo '<h1 style="color: red;">Exception!</h1>';
            echo '<table>';
            echo '<tr><td>Exception</td><td>&nbsp;&nbsp;&nbsp;&nbsp;Message</td></tr>';
            printf('<tr><td>%s:%d</td><td>&nbsp;&nbsp;&nbsp;&nbsp;%s</td></tr>', $exception->getFile(), $exception->getLine(), $exception->getMessage());
            echo '</table>';
            die('here');
            header('Internal Server Error', TRUE, 500);
            die();
        }

        return TRUE;
    }

}
