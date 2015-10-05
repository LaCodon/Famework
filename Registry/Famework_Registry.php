<?php

namespace Famework\Registry;

use Famework\View\Famework_View;
use Famework\Registry\Famework_Exception_Registry_Error;
use Famework\Db\Famework_Exception_Database_Fail;

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
        if (isset($GLOBALS['\famework_env'])) {
            return $GLOBALS['\famework_env'];
        }
        return NULL;
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
        if(!isset($GLOBALS['\famework_db'])) {
            $GLOBALS['\famework_db'] = NULL;
        }
        
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
        if (isset($GLOBALS['\famework_view'])) {
            return $GLOBALS['\famework_view'];
        }

        return NULL;
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
     * @param bool $strict Whether to throw an exception if name is not used
     * @return -/- The value
     * @throws Famework_Exception_Registry_Error
     */
    public static function get($name, $strict = TRUE) {
        if (!isset($GLOBALS[$name])) {
            if ($strict) {
                throw new Famework_Exception_Registry_Error('Missing entry with key "' . $name . '"');
            } else {
                return NULL;
            }
        }

        return $GLOBALS[$name];
    }

}
