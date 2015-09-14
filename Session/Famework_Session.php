<?php

namespace Famework\Session;

class Famework_Session {

    protected $_namespace = '/';

    public static function start() {
        if (session_start() === FALSE) {
            throw new Exception_Session_Start();
        }
    }

    public function setNamespace($namespace) {
        $this->_namespace = $namespace . '/';
    }

    public function getNamespace() {
        return $this->_namespace;
    }

    public function set($name, $value) {
        $_SESSION[$this->_namespace . $name] = $value;
    }

    public function get($name) {
        return $_SESSION[$this->_namespace . $name];
    }

    public function getAll() {
        $result = array();

        foreach ($_SESSION as $name => $value) {
            if (substr($name, 0, strlen($this->_namespace)) === $this->_namespace) {
                $result[substr($name, strlen($this->_namespace))] = $value;
            }
        }

        return $result;
    }

    public function regenerateId($delete_old_session = FALSE) {
        session_regenerate_id($delete_old_session);
    }

    public function destroySession() {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }

        session_destroy();
    }

}
