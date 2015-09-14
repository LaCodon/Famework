<?php

namespace Famework\Db;

use Famework\Config\Famework_Config;

class Famework_Database {

    public static function loadInstance(Famework_Config $config) {
        $dsn = $config->getValue('database', 'db_dsn');
        if ($dsn === NULL) {
            return NULL;
        }
        
        $dbuser = $config->getValue('database', 'db_user');
        $dbpass = $config->getValue('database', 'db_pass');

        return new \PDO($dsn, $dbuser, $dbpass);
    }

    /**
     * Convert SQL result into an object
     * @param array $fetchAll Result of $stm->fetchAll()
     * @param string $class The class type of the created object
     */
    public static function getAsObject($fetchAll, $class = '\stdClass') {
        $result = array();

        foreach ($fetchAll as $row) {
            $obj = new $class();
            foreach ($row as $key => $value) {
                $obj->$key = $value;
            }
            $result[] = $obj;
        }

        if (count($result) === 0) {
            return NULL;
        }

        if (count($result) === 1) {
            return $result[0];
        }

        return $result;
    }

}
