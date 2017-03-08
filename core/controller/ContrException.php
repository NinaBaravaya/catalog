<?php

defined('PROM') or exit('Access denied');

class ContrException extends Exception {

    protected $message;

    public function __construct($text,$path = FALSE) {
        $this->message = $text;

        $file = $this->getFile();
        $line = $this->getLine();

        $_SESSION['error']['file'] = $file;
        $_SESSION['error']['line'] = $line;

        if($path) {
            $_SESSION['error']['path'] = $path;
        }

        header("Location:".SITE_URL.'error/mes/'.rawurlencode($text));
    }
}
?>