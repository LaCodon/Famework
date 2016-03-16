<?php

namespace Famework\View;

use Famework\Request\Famework_Request;

class Famework_View {

    /**
     * @var Famework_View_Frame
     */
    protected $__frame;
    protected $_metas = array();
    protected $_layoutoff = FALSE;
    protected $_request;
    protected $_controller;
    protected $_action;

    /**
     * @var \Famework\View\Famework_View_Frame_Controller 
     */
    protected $_framecontroller;

    public function __construct(Famework_Request $request = NULL) {
        $this->__frame = new Famework_View_Frame();
        $this->_request = $request;
    }

    public function render($viewpath) {
        $controller = strtolower(str_replace('Controller', '', $this->_controller));
        $action = strtolower(str_replace('Action', '', $this->_action));

        $path = \Famework\combinePath(array($viewpath, $controller, $action . '.php'));

        if (is_readable($path)) {
            if ($this->_layoutoff === FALSE) {
                foreach ($this->_metas as $meta) {
                    $this->__frame->addHeadElement($meta);
                }

                $this->__frame->theDoctype();
                $this->__frame->theHeader();
                if ($this->_framecontroller !== NULL) {
                    $this->_framecontroller->renderTop();
                }
                require $path;
                if ($this->_framecontroller !== NULL) {
                    $this->_framecontroller->renderBottom();
                }
                $this->__frame->theFooter();
            } else {
                require $path;
            }
        } else {
            throw new Famework_Exception_Missing_View($action);
        }
    }

    /**
     * Add CSS to the view
     * @param string $css URL or CSS as string
     * @param string $media
     */
    public function addCSS($css, $media = 'screen') {
        if (strpos($css, '{') === FALSE) {
            // filepath
            $this->__frame->addHeadElement('<link rel="stylesheet" media="' . $media . '" type="text/css" href="' . $css . '">');
        } else {
            // plain CSS
            $this->__frame->addCSS($css);
        }
    }

    /**
     * Set the html lang attribute
     * @param string $lang
     */
    public function setLang($lang) {
        $this->__frame->_lang = $lang;
    }

    /**
     * Add JS to view
     * @param string $js URL to JS file
     */
    public function addJS($js) {
        $this->__frame->addHeadElement('<script src="' . $js . '"></script>');
    }

    /**
     * Add a meta tag to the html head
     * @param string $name
     * @param string $content
     */
    public function addMeta($name, $content) {
        $this->_metas[$name] = '<meta name="' . $name . '" content="' . $content . '">';
    }

    /**
     * Add an HTML tag to the HTML head section
     * @param string $elementHtml The HTML code for the element, e.g. <code>&lt;link rel="shortcut icon" href="favicon.ico"&gt;</code>
     */
    public function addHeadElement($elementHtml) {
        $this->__frame->addHeadElement($elementHtml);
    }

    /**
     * Turn the automatic layout (html frame) off
     */
    public function turnLayoutOff() {
        $this->_layoutoff = TRUE;
    }

    /**
     * Set the html <title>
     * @param string $string
     */
    public function title($string) {
        $this->__frame->_title = $string;
    }

    /**
     * Set the html meta-description
     * @param string $string
     */
    public function description($string) {
        $this->__frame->_description = $string;
    }

    /**
     * Get the value of an URI path param
     * @param string $name The key which was set in the routes.ini
     * @return string The value
     */
    public function getRequestParam($name) {
        if ($this->_request === NULL) {
            return NULL;
        }

        return $this->_request->getRequestParam($name);
    }

    public function getController() {
        return $this->_controller;
    }

    public function getAction() {
        return $this->_action;
    }

    public function setController($controller) {
        $this->_controller = $controller;
    }

    public function setAction($action) {
        $this->_action = $action;
    }

    /**
     * Use this function if you want to generalise some
     * parts inside the <body> tag of your HTML
     * @param \Famework\View\Famework_View_Frame_Controller $object
     */
    public function setFrameController($object) {
        $this->_framecontroller = $object;
    }

}
