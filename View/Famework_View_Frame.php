<?php

namespace Famework\View;

class Famework_View_Frame {

    protected $_css = array();
    protected $_headelements = array();
    public $_title = 'A Famework website';
    public $_description = '';
    public $_lang = '';

    public function theDoctype() {
        echo '<!DOCTYPE html>' . PHP_EOL;
    }

    public function theHeader() {
        echo '<html' . ($this->_lang === '' ? '' : ' lang="' . $this->_lang . '"') . '>' . PHP_EOL
        . '<head>' . PHP_EOL
        . '<title>' . $this->_title . '</title>' . PHP_EOL
        . '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . PHP_EOL
        . '<meta name="description" content="' . $this->_description . '">' . PHP_EOL
        . '<meta name="viewport" content="width=device-width, initial-scale=1">' . PHP_EOL;

        foreach ($this->_headelements as $element) {
            echo $element . PHP_EOL;
        }

        if (count($this->_css) > 0) {
            echo '<style>' . PHP_EOL;
            foreach ($this->_css as $css) {
                echo $css . PHP_EOL;
            }
            echo '</style>' . PHP_EOL;
        }

        echo '</head>' . PHP_EOL
        . '<body>' . PHP_EOL;
    }

    public function theFooter() {
        echo '</body>' . PHP_EOL
        . '</html>';
    }

    public function addCSS($css) {
        $this->_css[] = $css;
    }

    public function addHeadElement($element) {
        $this->_headelements[] = $element;
    }

}
