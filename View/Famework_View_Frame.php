<?php

namespace Famework\View;

class Famework_View_Frame {

    protected $_css = array();
    protected $_headelements = array();
    public $_title = 'A Famework website';
    public $_description = '';

    public function theDoctype() {
        echo '<!DOCTYPE html>';
    }

    public function theHeader() {
        echo '<html>'
        . '<head>'
        . '<title>' . $this->_title . '</title>'
        . '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'
        . '<meta name="description" content="' . $this->_description . '">'
        . '<meta name="viewport" content="width=device-width, initial-scale=1">';

        foreach ($this->_headelements as $element) {
            echo $element;
        }

        if (count($this->_css) > 0) {
            echo '<style>';
            foreach ($this->_css as $css) {
                echo $css;
            }
            echo '</style>';
        }

        echo '</head>'
        . '<body>';
    }

    public function theFooter() {
        echo '</body>'
        . '</html>';
    }

    public function addCSS($css) {
        $this->_css[] = $css;
    }

    public function addHeadElement($element) {
        $this->_headelements[] = $element;
    }

}
