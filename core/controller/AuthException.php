<?php
defined('PROM') or exit('Access denied');
class AuthException extends Exception{
    //класс обрабатывает исключения связанные с авторизацияей пользователя
    public function __construct($text)
    {
        $this->message= $text;//записываем сообщение об ошибке в сво-во message
        $_SESSION['auth'] = $text;//сохраняем сообщение ошибки
    }
}