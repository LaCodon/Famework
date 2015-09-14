<?php

namespace Famework\LaCodon\File;

class File_Upload {

    const ERROR_NOSUCHFILE = 0;
    const ERROR_SUSPICIOUSFILE = 1;
    const ERROR_WRONGFILE = 2;

    protected $_files;
    protected $_validators = array();
    protected $_lastError;

    public function __construct() {
        $this->_files = $_FILES;
        $_FILES = array();
    }

    public function addValidator(Upload_Validator $validator) {
        $this->_validators[] = $validator;
    }

    /**
     * Get the validated $_FILE[$name] array of an uploaded file
     * @param string $name The HTML name of the file input
     * @return array <i>NULL</i> if an error occured (check getLastError())
     */
    public function getUploadedFile($name) {
        if (!isset($this->_files[$name])) {
            $this->_lastError = self::ERROR_NOSUCHFILE;
            return NULL;
        }

        $file = $this->_files[$name];

        if (!is_uploaded_file($file['tmp_name'])) {
            $this->_lastError = self::ERROR_SUSPICIOUSFILE;
            return NULL;
        }

        if (!$this->validate($file)) {
            $this->_lastError = self::ERROR_WRONGFILE;
            return NULL;
        }
    }

    private function validate($file) {
        $extensions = $this->mergeExtensions();
        $mimes = $this->mergeMimes();

        if (in_array($file['type'], $mimes['disallowed']) || in_array($this->getExtension($file), $extensions['disallowed'])) {
            // mime or extension is blacklisted
            return FALSE;
        }

        if (in_array($file['type'], $mimes['allowed']) && in_array($this->getExtension($file), $extensions['allowed'])) {
            // mime and extension is allowed
            return TRUE;
        }

        /** nothing happened yet
         * options:
         *  1) no validator is set => everything is allowd => return TRUE
         *  2) we only have blacklists => file is not on blacklist => return TRUE
         */
        if (count($this->_validators) === 0 || $this->onlyBlacklists()) {
            return TRUE;
        }

        /**
         *  3) we only habe whitelists => file is not on whitelist => return FALSE
         *  4) something else => bad => return FALSE
         */
        return FALSE;
    }

    private function getExtension($file) {
        // get whole name
        $name = $file['name'];
        // split by dots
        $parts = explode('.', $name);
        // return last element of array
        return $parts[count($parts) - 1];
    }

    public function getLastError() {
        return $this->_lastError;
    }

    private function mergeExtensions() {
        $result = array('allowed' => array(), 'disallowed' => array());

        foreach ($this->_validators as $validator) {
            if ($validator->isBlackList()) {
                $result['disallowed'] = array_merge($result['disallowed'], $validator->getExtensions());
            } else {
                $result['allowed'] = array_merge($result['allowed'], $validator->getExtensions());
            }
        }

        return $result;
    }

    private function mergeMimes() {
        $result = array('allowed' => array(), 'disallowed' => array());

        foreach ($this->_validators as $validator) {
            if ($validator->isBlackList()) {
                $result['disallowed'] = array_merge($result['disallowed'], $validator->getMimes());
            } else {
                $result['allowed'] = array_merge($result['allowed'], $validator->getMimes());
            }
        }

        return $result;
    }

    private function onlyBlacklists() {
        foreach ($this->_validators as $validator) {
            if (!$validator->isBlackList()) {
                return FALSE;
            }
        }

        return TRUE;
    }

}
