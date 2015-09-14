<?php

namespace Famework\LaCodon\File;

class Upload_Validator {

    const MIME_AVI = 'application/x-troff-msvideo|video/avi|video/msvideo|video/x-msvideo';
    const MIME_BMP = 'image/bmp|image/x-windows-bmp';
    const MIME_DOC = 'application/msword';
    const MIME_EXE = 'application/octet-stream';
    const MIME_GIF = 'image/gif';
    const MIME_GZIP = 'application/x-gzip|application/x-gzip';
    const MIME_HTML = 'text/html';
    const MIME_ICO = 'image/x-icon';
    const MIME_JPG = 'image/jpeg|image/pjpeg';
    const MIME_MP3 = 'audio/mpeg3|audio/x-mpeg-3|video/mpeg|video/x-mpeg';
    const MIME_PLAIN = 'text/plain';
    const MIME_PPT = 'application/mspowerpoint|application/powerpoint|application/vnd.ms-powerpoint|application/x-mspowerpoint';
    const MIME_XLS = 'application/excel|application/vnd.ms-excel|application/x-excel|application/x-msexcel';
    const MIME_XML = 'application/xml|text/xml';
    const MIME_ZIP = 'application/x-compressed|application/x-zip-compressed|application/zip|multipart/x-zip';

    private $_extensions;
    private $_mimes = array();
    private $_disallowed = FALSE;

    public function __construct(array $extensions = NULL, $mime = NULL) {
        $this->_extensions = $extensions;
        $this->_mimes = explode('|', $mime);
    }

    /**
     * Use this method to add this validator to the blacklist
     */
    public function disallow() {
        $this->_disallowed = TRUE;
    }

    /**
     * Check whether this validator is meant to be a blacklist
     * @return bool
     */
    public function isBlackList() {
        return $this->_disallowed;
    }

    /**
     * Get mimes of validator as array
     * @return array
     */
    public function getMimes() {
        return $this->_mimes;
    }

    /**
     * Get extensions of validator as array
     * @return array
     */
    public function getExtensions() {
        return $this->_extensions;
    }

    public function addExtension($extension) {
        $this->_extensions[] = $extension;
    }

    public function addMime($mime) {
        if (strpos($mime, '|') === FALSE) {
            $this->_mimes[] = $mime;
        } else {
            // user added a list of mimes (e.g. one of the consts)
            $this->_mimes = explode('|', $mime);
        }
    }

}
