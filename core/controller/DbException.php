<?php
defined('PROM') or exit('Access denied');
class DbException extends Exception {//контроллер обрабатывает ошибки возникающие в Model_Driver

    protected $message;

    public function __construct($text) {
        $this->message = $text;

        $file = $this->getFile();
        $line = $this->getLine();

        $_SESSION['error']['file'] = $file;
        $_SESSION['error']['line'] = $line;

        header("Location:".SITE_URL.'error/mes/'.rawurlencode($text));
    }
}
?>