<?php
define('PROM',TRUE);
header("Content-Type:text/html;charset=utf-8");
error_reporting(0);
session_start();
require "config.php";

set_include_path(get_include_path()
    .PATH_SEPARATOR.CONTROLLER
    .PATH_SEPARATOR.MODEL
    .PATH_SEPARATOR.LIB
);

spl_autoload_register(function($class_name) {

    if(!include_once ($class_name.".php")) {

        try {
            throw new ContrException($class_name.' Не правильный файл для подключения');
        }
        catch(ContrException $e) {
            echo $e->getMessage();
        }
    }
});
try{
    $obj = Route_Controller::get_instance();
    $obj->route();
}
catch(ContrException $e) {
    return;//ничего здесь делать не будем так как,
    // в классе ContException мы перенаправляем пользователя на Error_Controller

}

?>