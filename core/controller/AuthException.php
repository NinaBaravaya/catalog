<?php
class AuthException extends Exception{
    //класс обрабатывает исключения связанные с авторизацияей пользователя
    public function __construct($text)
    {
        $this->message= $text;//записываем сообщение об ошибке в сво-во message
        $_SESSION['auth'] = $text;//сохраняем сообщения ошибки
    }
}