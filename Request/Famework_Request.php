<?php

namespace Famework\Request;

class Famework_Request {

    protected $_params;

    public function __construct($params) {
        $this->_params = array();

        if (!is_array($params)) {
            return;
        }

        foreach ($params as $param => $value) {
            if ($param !== '' && $value !== '') {
                $this->_params[$param] = $value;
            }
        }
    }

    /**
     * Redirect user via HTTP header and exit the application
     * @param string $url Where to redirect to
     * @param int $code HTTP Statuscode
     */
    public static function redirect($url, $code = 302) {
        header('Location: ' . $url, TRUE, $code);
        exit();
    }

    public function getRequestParam($name) {
        if (isset($this->_params[$name])) {
            return $this->_params[$name];
        }

        return NULL;
    }

}
