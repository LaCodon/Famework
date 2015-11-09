<?php

namespace Famework;

use Famework\Request\Famework_Request;
use Famework\Session\Famework_Session;
use Famework\Handler\Famework_Handler;
use Famework\Registry\Famework_Registry;
use Famework\Config\Famework_Config;
use Famework\Db\Famework_Database;
use Famework\View\Famework_View;

if (!defined('FAMEWORK_ROOT')) {
    define('FAMEWORK_ROOT', dirname(__FILE__));
}

class Famework {

    const ENV_DEV = 1;
    const ENV_PROD = 2;

    /**
     * @var Famework_Config 
     */
    private $_routes;
    private $_controller;
    private $_action;
    // from config
    private $_http_root;
    private $_view_path;
    // params
    private $_params;

    /**
     * @var \Famework\Request\Famework_Request
     */
    private $_request;

    /**
     * load the config.ini and routes.ini (or the ini strings)
     * <b>Format for routes.ini:</b><br><br>
     * <code>
     * [index]<br>
     * famework_route = {root}<br>
     * famework_controller = index<br>
     * famework_action = index<br>
     * <br>
     * [default]<br>
     * famework_route = {root}/:controller/:action<br>
     * famework_controller = :controller<br>
     * famework_action = :action<br>
     * <br>
     * [custom]<br>
     * famework_route = {root}/test/playground<br>
     * famework_controller = tester<br>
     * famework_action = index<br>
     * </code>
     * @param \Famework\Config\Famework_Config $config
     * @param \Famework\Config\Famework_Config $routes
     */
    public function __construct(Famework_Config $config, Famework_Config $routes) {
        // set encoding
        mb_internal_encoding('UTF-8');
        // set application environment
        $env = $config->getValue('famework', 'env');
        if (strtolower($env) === 'dev' || strtolower($env) === 'development' || strtolower($env) === '1') {
            Famework_Registry::setEnv(self::ENV_DEV);
        } elseif (Famework_Registry::getEnv() === NULL) {
            Famework_Registry::setEnv(self::ENV_PROD);
        }
        // interpret the configuration data
        $this->useConfig($config);
        // save route for further use
        $this->_routes = $routes;
        // connect with database if required
        $dbinstance = Famework_Database::loadInstance($config);
        if ($dbinstance !== NULL) {
            Famework_Registry::setDb($dbinstance);
        }
    }

    private function useConfig($config) {
        $this->_http_root = $config->getValue('famework', 'public_root');
        if ($this->_http_root === NULL) {
            $this->_http_root = '/';
        }

        $this->_view_path = $config->getValue('famework', 'view_path');
        if ($this->_view_path === NULL) {
            $this->_view_path = '/';
        }

        if ($config->getValue('famework', 'use_session') == TRUE) {
            Famework_Session::start();
        }
    }

    /**
     * Call this to handle a request.
     * Then call Famework::loadController();
     */
    public function handleRequest($path = NULL) {
        $result = array();

        if ($path === NULL) {
            $requestUri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
        } else {
            $requestUri = $path;
        }

        $routes = $this->_routes->getByKey('famework_route');

        foreach ($routes as $route) {
            $route['value'] = normalizePath(str_replace('{root}', $this->_http_root, $route['value']));
            if (substr($requestUri, -1, 1) !== '/') {
                $requestUri .= '/';
            }
            if (substr($route['value'], -1, 1) !== '/') {
                $route['value'] .= '/';
            }
            if ($route['value'] === $requestUri) {
                $result['controller'] = $this->_routes->getValue($route['group'], 'famework_controller');
                $result['action'] = $this->_routes->getValue($route['group'], 'famework_action');
                break;
            } else {
                $pattern = $this->getRoutePattern($route['value']);

                // find params
                preg_match_all('/:(\w+)/', $pattern, $params);

                foreach ($params[1] as $param) {
                    $pattern = str_replace(':' . $param, '(?<' . $param . '>.*?)', $pattern);
                }

                if (($pregres = preg_match($pattern, $requestUri, $matches)) !== FALSE && $pregres === 1) {
                    if (!isset($matches['crtl'])) {
                        $matches['crtl'] = '';
                    }
                    if (!isset($matches['act'])) {
                        $matches['act'] = '';
                    }
                    $result['controller'] = str_replace(':controller', $matches['crtl'], $this->_routes->getValue($route['group'], 'famework_controller'));
                    $result['action'] = str_replace(':action', $matches['act'], $this->_routes->getValue($route['group'], 'famework_action'));

                    // add params
                    $this->_params = array();
                    foreach ($params[1] as $param) {
                        if (strpos($matches[$param], '/') !== FALSE) {
                            // something went wrong because param is URI fragment
                            unset($result['controller']);
                            unset($result['action']);
                            break;
                        }
                        $this->_params[$param] = $matches[$param];
                    }
                    break;
                }
            }
        }

        if (isset($result['controller']) && isset($result['action'])) {
            if (strpos($result['controller'], '/') !== FALSE || strpos($result['action'], '/') !== FALSE) {
                // something went wrong because controller or action is URI fragment
                unset($result['controller']);
                unset($result['action']);
            } else {
                $this->setController($result['controller']);
                $this->setAction($result['action']);
            }
        }

        $this->_request = new Famework_Request($this->_params);
    }

    private function getRoutePattern($route) {
        $pattern = str_replace(':controller', '(?<crtl>.*?)', $route);
        $pattern = str_replace(':action', '(?<act>.*?)', $pattern);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';

        return $pattern;
    }

    private function setController($string) {
        $string = ucfirst(strtolower($string)) . 'Controller';
        $this->_controller = $string;
    }

    private function setAction($string) {
        $actionparts = explode('.', $string);
        $action = '';
        foreach ($actionparts as $part) {
            $action .= strtolower($part);
        }
        $this->_action = $action . 'Action';
    }

    /**
     * Call this to load the controller, action and view.
     * Call Famework::handleRequest() <b>before</b>!
     */
    public function loadController($nooutput = FALSE) {
        if ($this->_controller === NULL || $this->_action === NULL) {
            $this->_controller = 'IndexController';
            $this->_action = 'notfoundAction';
            header('HTTP/1.0 404 Not Found');
        }

        // we need the default 404 error page, because developer provides none
        if (!class_exists($this->_controller, TRUE) || get_parent_class($this->_controller) !== 'Famework\Controller\Famework_Controller') {
            $this->default404($nooutput);
        }

        // get view
        $pageview = new Famework_View($this->_request);
        $pageview->setController($this->_controller);
        $pageview->setAction($this->_action);

        // save view in registry
        Famework_Registry::setView($pageview);

        // get controller
        $ctrlClass = new $this->_controller($pageview);
        $ctrlClass->init();

        if (!method_exists($ctrlClass, $this->_action)) {
            $this->default404($nooutput);
        }

        // run action
        $action = $this->_action;
        $ctrlClass->$action();

        // render view
        $pageview->render($this->_view_path);
    }

    private function default404($nooutput) {
        if ($nooutput === TRUE) {
            exit();
        }
        echo '<!DOCTYPE html>
        <html>
            <head>
                <title>404 Not Found</title>
            </head>
            <body>
                <h1>Not Found</h1>
                <p>The requested URL was not found on this server.</p>
            </body>
        </html>';
        exit();
    }

    /**
     * register the default error- and exceptionhandler for developers
     */
    public static function registerDeafaultHandler() {
        Famework_Handler::registerDeafaultHandler();
    }

    /**
     * Get the value of an URI path param
     * @param string $name The key which was set in the routes.ini
     * @return string The value
     */
    public function getRequestParam($name) {
        if ($this->_request !== NULL) {
            return $this->_request->getRequestParam($name);
        }

        return NULL;
    }

    public function getController() {
        return $this->_controller;
    }

    public function getAction() {
        return $this->_action;
    }

}

/**
 * Combine several path parts to one path string
 * @param array $paths The path parts
 * @return string the combined path
 */
function combinePath(array $paths = NULL) {
    if (count($paths) === 0) {
        return NULL;
    }

    if (count($paths) === 1) {
        return $paths[0];
    }

    $result = $paths[0];

    for ($i = 1; $i < count($paths); $i++) {
        if (substr($result, -1) !== '/') {
            if (substr($paths[$i], 0, 1) !== '/') {
                $result .= '/' . $paths[$i];
            } else {
                $result .= $paths[$i];
            }
        } else {
            if (substr($paths[$i], 0, 1) !== '/') {
                $result .= $paths[$i];
            } else {
                $result .= substr($paths[$i], 1);
            }
        }
    }

    return str_replace('\\', '/', normalizePath($result));
}

/**
 * Remove double, trible, ... slashes from a string
 * @param string $path
 * @return string
 */
function normalizePath($path) {
    while (strpos($path, '//') !== FALSE) {
        $path = str_replace('//', '/', $path);
    }

    return $path;
}

require 'loader.php';
