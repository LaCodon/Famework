<?php

namespace Famework\Config;

use Famework\Registry\Famework_Registry;

class Famework_Config {

    protected $_config;

    /**
     * Load a config to work on with
     * @param string $config path to config or config as string
     */
    public function __construct($config) {
        $this->loadConfig($config);
    }

    /**
     * Load a config to work on with
     * @param string $config path to config or config as string
     */
    public function loadConfig($config) {
        if (is_readable($config)) {
            // file
            $data = parse_ini_file($config, TRUE);
        }

        if (is_string($config)) {
            // string
            $data = parse_ini_string($config, TRUE);
        }

        if ($data === FALSE) {
            throw new Famework_Exception_Config_Invalidconfig();
        }

        $this->_config = $data;
    }

    /**
     * Get a group from the config
     * @param string $name The group name in the config
     * @param bool $strict <b>TRUE</b> if an exception should be thrown on missing group
     * @return array key => value pairs of the config group
     * @throws Famework_Exception_Config_Nosuchgroup
     */
    public function getGroup($name, $strict = FALSE) {
        if (isset($this->_config[$name])) {
            return $this->_config[$name];
        }

        if ($strict === TRUE) {
            throw new Famework_Exception_Config_Nosuchgroup($name);
        }

        return NULL;
    }

    /**
     * Get all values with a certain key no matter in which group they are
     * @param string $searchkey The key to search values for
     * @return array array(array('value' => x, 'group' => y))
     */
    public function getByKey($searchkey) {
        return $this->getByKeyRecursive($searchkey, $this->_config);
    }

    /**
     * Helper function for $this->getByKey()
     */
    private function getByKeyRecursive($searchkey, $array) {
        $result = array();

        foreach ($array as $groupname => $group) {
            if (is_array($group)) {
                foreach ($group as $key => $value) {
                    if ($key == $searchkey && !is_array($value)) {
                        $result[] = array('value' => $value, 'group' => $groupname);
                    } elseif ($key == $searchkey && is_array($value)) {
                        foreach ($value as $element) {
                            $result[] = array('value' => $element, 'group' => $groupname);
                        }
                    } elseif (is_array($value)) {
                        $result = array_merge($result, $this->getByKeyRecursive($searchkey, $value));
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Get the value of a certain key in a certain group
     * @param string $group The ini group
     * @param string $key The ini key
     * @return string The ini value
     */
    public function getValue($group, $key) {
        $grouparr = $this->getGroup(self::getConfigGroup($group));
        if (!isset($grouparr[$key])) {
            // fallback to prod vars if in dev mode
            if (Famework_Registry::getEnv() === \Famework\Famework::ENV_DEV) {
                $grouparr = $this->getGroup(str_replace('_dev', '', $group));
                if (!isset($grouparr[$key])) {
                    return NULL;
                }
            } else {
                return NULL;
            }
        }
        return $grouparr[$key];
    }

    public static function getConfigGroup($group) {
        if (Famework_Registry::getEnv() === \Famework\Famework::ENV_DEV) {
            $group .= '_dev';
        }

        return $group;
    }

}
