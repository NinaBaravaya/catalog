<?php
defined('PROM') or exit("Access denied");//будем проверять существует ли константа

define('CONTROLLER', 'core/controller');//задаем путь к контроллеру
define('MODEL', 'core/model');//задаем путь к модели
define('VIEW','template/shablon/');//задаем путь к шаблону

define('LIB','lib');//задаем путь к шаблону

//чтобы не писать длинные значения путей
define('SITE_URL','/');//константа хранит адрес сайта, т.е.путь к папке, где лежит индексный файл сайта
//единая точка входа

define('QUANTITY',6);//кол-во товаров для постраничной навигации

define('QUANTITY_LINKS',3);//число ссылок по обеим сторонам от текущей страницы

//путь к директории с изображениями товаров
define('UPLOAD_DIR','images/');

//доступ к бд
define('HOST','localhost');//сервер бд
define('USER','root');
define('PASSWORD','');
define('DB_NAME','catalog');

define('IMG_WIDTH',116);
define('IMG_HEIGHT',175);

define('NOIMAGE','no_image.jpg');

define('FEALT',1);//кол-во дней, на которое будет забанен пользователь

////////////////////////безопасноть сайта
define("VERSION", '110');//версия файла кук, которые мы создаем
define("KEY","GDSHG4385743HGSDHdkfgjdfk4653475JSGHDJSDSKJDF476354");
define("EXPIRATION",6000);//время после которого пользовтелю необходимо переавторизироваться
define("VARNING_TIME",3000);

////////////////////////безопасноть сайта

//массив доп настроек
$conf = array(
    'styles' => array(
        'css/templatemo_style.css',
        'css/ddsmoothmenu.css',
        'css/styles.css',
        'css/lightbox.css',
        'css/error.css',
    ),
    'scripts' => array(
        'JS/jquery-1.7.2.min.js',
        'JS/jquery-ui-1.8.20.custom.min.js',
        'JS/jquery.cookie.js',
        'JS/js.js',
        'JS/script.js',
    ),
    'styles_admin' => array(
        'style.css'
    ),
    'scripts_admin' => array(
        'JS/func.js',
        'JS/jquery-1.7.2.min.js',
        'JS/jquery-ui-1.8.20.custom.min.js',
        'JS/jquery.cookie.js',
        'JS/js.js',
        'JS/prelod.js',
        'JS/script.js',

        'JS/tiny_mce/tiny_mce.js',
        'JS/tiny_script.js'
    ),
);//будем программно(автоматически ) подключать скрипты js
