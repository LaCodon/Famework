<?php

namespace Famework\LaCodon\Param;

class Paramhandler {

    const GET = 1;
    const POST = 2;
    const COOKIE = 4;

    protected $_post;
    protected $_get;
    protected $_cookie;
    protected $_restriction;
    protected $_methods;

    public function __construct() {
        $this->_post = $_POST;
        $_POST = array();

        $this->_get = $_GET;
        $_GET = array();

        $this->_cookie = $_COOKIE;
        $_COOKIE = array();

        $this->bindMethods(self::GET | self::POST | self::COOKIE);
    }

    /**
     * Get the value of a param in set methods.
     * Available are GET, POST and COOKIE.
     * See Famework_Paramhandler::bindMethods for limitation to special methods
     * @param string $name The param name
     * @param bool $required Whether the parameter is required. If not set and not required, function will return <i>NULL</i>
     * @param int $min The minimum strlen. <i>NULL</i> for no limitation.
     * @param int $max The maximum strlen. <i>NULL</i> for no limitation.
     * @return string The value of the param
     * @throws Exception_Param If param doesn't fit the conditions
     */
    public function getValue($name, $required = TRUE, $min = NULL, $max = NULL) {
        $value = $this->searchValue($name);

        if ($required === TRUE && ($value === NULL || $value === '')) {
            throw new Exception_Param('The param "' . $name . '" has to be set.');
        } elseif ($required === FALSE && ($value === NULL || $value === '')) {
            return NULL;
        }

            if (is_int($min) && strlen(mb_convert_encoding($value, 'UTF-8')) < $min) {
            throw new Exception_Param('The value of the param "' . $name . '" is too short.');
        }

        if (is_int($max) && strlen(mb_convert_encoding($value, 'UTF-8')) > $max) {
            throw new Exception_Param('The value of the param "' . $name . '" is too long.');
        }

        return $value;
    }

    public function getInt($name, $require = TRUE, $min = NULL, $max = NULL) {
        $value = $this->getValue($name, $require);

        if (ctype_digit(strval($value)) === FALSE) {
            if ($require === TRUE) {
                throw new Exception_Param('The value of the param "' . $name . '" has the wrong format.');
            } else {
                return NULL;
            }
        }

        $value = intval($value);

        try {
            if (is_int($min) && $value < $min) {
                throw new Exception_Param('The value of the param "' . $name . '" is too small.');
            }

            if (is_int($max) && $value > $max) {
                throw new Exception_Param('The value of the param "' . $name . '" is too big.');
            }
        } catch (Exception_Param $e) {
            if ($require === TRUE) {
                throw $e;
            } else {
                return NULL;
            }
        }

        return $value;
    }

    private function searchValue($key) {
        foreach ($this->_methods as $method) {
            foreach ($method as $name => $value) {
                if ($name === $key) {
                    return $value;
                }
            }
        }
        return NULL;
    }

    /**
     * Set the methods where to searche data in
     * e.g.: Famework_Paramhandler::GET | Famework_Paramhandler::POST
     * @param int $methods The Methods connected with |
     */
    public function bindMethods($methods) {
        $this->_restriction = $methods;
        $newmethods = array();
        if (($this->_restriction & self::GET) === self::GET) {
            $newmethods[] = $this->_get;
        }
        if (($this->_restriction & self::POST) === self::POST) {
            $newmethods[] = $this->_post;
        }
        if (($this->_restriction & self::COOKIE) === self::COOKIE) {
            $newmethods[] = $this->_cookie;
        }
        $this->_methods = $newmethods;
    }

}
