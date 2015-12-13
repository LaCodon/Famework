[![Build Status](https://travis-ci.org/LaCodon/Famework.svg?branch=master)](https://travis-ci.org/LaCodon/Famework)
[![CC0](https://licensebuttons.net/p/zero/1.0/80x15.png)](http://creativecommons.org/publicdomain/zero/1.0/)
# Famework
Famework is a simple to use PHP Framwork to easily create splendid but lightweight web applications, based on a MVC pattern.

## Minimal setup
In order to make Famework ready to use, you have to do the following easy steps:

1. Download the latest version of Famework from https://github.com/LaCodon/Famework (choose the latest tag version) and unzip the folder in any folder on your webserver.
2. Create your own project in a seperate folder an make sure it has at least the following folder structure:

    ```
    |- index.php
    |- .htaccess
    |- config/
        |- config.ini
        |- routes.ini
    |- controller/
        |- IndexController.php
    |- view/
        |- index/
            |- index.php
    ```
3. Paste the following in your /.htaccess file  ("/" is the root of your project folder)
    ```
    <IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.php [L]
    </IfModule>
    ```
4. Paste the following in your /index.php
    ```php
    <?php

    use Famework\Famework;
    use Famework\Config\Famework_Config;
    use Famework\Registry\Famework_Registry;
    
    // the root folder of the application
    define('APPLICATION_PATH', __DIR__ . DIRECTORY_SEPARATOR);
    // the root folder to use in URLS; usually you can keep this as it is
    define('HTTP_ROOT', str_replace(basename(__FILE__), '', $_SERVER['PHP_SELF']) . '/');
    // the path to your /view folder
    define('VIEW_PATH', APPLICATION_PATH . 'view');
    // require Famework; yes, that's it!
    require '../Famework/Famework.php';
    
    // activate error_reporting and define default error and exception handlers
    // you can replace this with your own handlers
    // DEACTIVATE error_reporing on production
    error_reporting(E_ALL | E_STRICT);
    Famework::registerDeafaultHandler();
    // Famework::ENV_PROD for producation
    // this is just a nice constant
    Famework_Registry::setEnv(Famework::ENV_DEV);
    
    // initialize Famework
    $famwork = new Famework(new Famework_Config(APPLICATION_PATH . 'config/config.ini'), new Famework_Config(APPLICATION_PATH . 'config/routes.ini'));
    // this require statement is the simplest autoloader on earth, replace it with your own
    require './controller/IndexController.php';
    
    // handle request and load controller, because this is MVC pattern!
    $famwork->handleRequest();
    $famwork->loadController();
    ```
5. Paste the following in your /config/config.ini file
    ```ini
    [famework]
    ; make sure you use the correct PHP const names
    public_root = HTTP_ROOT
    view_path = VIEW_PATH
    ; true means, that Famework_Session::start() gets called (session_start())
    use_session = false
    
    [database]
    ; db connection information
    ;db_dsn = "mysql:dbname=test;127.0.0.1"
    ;db_user = dbUser
    ;db_pass = userPassword
    ```
6. Paste the following in your /config/routes.ini file
    ```ini
    
    [default]
    ; "{root}" means HTTP_ROOT
    ; e.g.: http://www.example.com/index/index calls IndexController::indexAction() and /view/index/index.php
    famework_route = {root}/:controller/:action
    famework_controller = :controller
    famework_action = :action
    ```
7. Paste the following in /controller/IndexController.php
    ```php
    <?php

    use Famework\Controller\Famework_Controller;
    
    class IndexController extends Famework_Controller {
    
        /**
         * this function is required
         */
        public function init() {
            // do some init for all actions
        }
    
        public function indexAction() {
            // sets the <title> tag to "Hello World"
            $this->_view->title('Hello World');
        }
        
    }
    ```
    
    
## More
More is comming soon, keep experimenting!
