<?php
defined('PROM') or exit('Access denied');
class ContrException extends Exception {//класс обрабатывает все ошибки связанные с контроллером

    protected $message;//сво-во хранит сообщение об ошибке

    public function __construct($text,$path = FALSE) {
        $this->message = $text;

        $file = $this->getFile();//имя файла, в котором произошла ошибка
        $line = $this->getLine();//номер строки, на которой произошла ошибка

        $_SESSION['error']['file'] = $file;//сохраняем получен выше данные в сессию
        $_SESSION['error']['line'] = $line;
        if($path) {
            $_SESSION['error']['path'] = $path;
        }

        header("Location:".SITE_URL.'error/mes/'.rawurlencode($text));//переадресуем пользователя на контроллер
        //error_controller (контроллер вывода ошибок) и передадим ему сообщение об ошибке
        //в зашифрованном виде (т.к. оно содержит кириллические символы)
    }
}
?>