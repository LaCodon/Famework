<?php

namespace Famework\Registry;

use Famework\View\Famework_View;

class Famework_Registry {

    /**
     * Set the application environment
     * @param int $env 1: DEVELOPMENT; 2: PRODUCTION
     */
    public static function setEnv($env) {
        $GLOBALS['\famework_env'] = $env;
    }

    /**
     * Get the application environment
     * @return int 1: DEVELOPMENT; 2: PRODUCTION
     */
    public static function getEnv() {
        return $GLOBALS['\famework_env'];
    }

    /**
     * Set global PDO object
     * @param \PDO $dbinstance
     */
    public static function setDb(\PDO $dbinstance) {
        $GLOBALS['\famework_db'] = $dbinstance;
    }

    /**
     * Get database instance
     * @return \PDO
     */
    public static function getDb() {
        if ($GLOBALS['\famework_db'] === NULL) {
            throw new Famework_Exception_Database_Fail('FATAL ERROR! Database not well configured!');
        }

        return $GLOBALS['\famework_db'];
    }

    public static function setView(Famework_View $view) {
        $GLOBALS['\famework_view'] = $view;
    }

    /**
     * @return Famework_View
     */
    public static function getView() {
        return $GLOBALS['\famework_view'];
    }

    /**
     * Set your own registry entry
     * @param string $name The identifier
     * @param -/- $value The value
     * @throws Famework_Exception_Registry_Error
     */
    public static function set($name, $value) {
        if (isset($GLOBALS[$name]) === FALSE) {
            $GLOBALS[$name] = $value;
        } else {
            throw new Famework_Exception_Registry_Error('Already have entry with key "' . $name . '"');
        }
    }

    /**
     * Get a registry entry
     * @param string $name The identifier
     * @return -/-
     * @throws Famework_Exception_Registry_Error
     */
    public static function get($name) {
        if (!isset($GLOBALS[$name])) {
            throw new Famework_Exception_Registry_Error('Missing entry with key "' . $name . '"');
        }

        return $GLOBALS[$name];
    }

}
