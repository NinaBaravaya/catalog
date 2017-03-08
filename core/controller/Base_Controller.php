<?php
defined('PROM') or exit('Access denied');

abstract class Base_Controller
{

    protected $request_url;

    protected $controller;

    protected $params;

    protected $styles, $styles_admin;

    protected $scripts, $scripts_admin;

    protected $error;

    protected $page;

    public function route()
    {
        //$obj = new $this->controller;//создаем объект контроллера
        // (однако создавать объект другого класса в абстр классе некрасиво)
        //$obj->request($this->params);//вызываем у объекта метод и передаем параметры для работы модели



           if (class_exists($this->controller)) {//сущ-т ли такой класс

            $ref = new ReflectionClass($this->controller);//Reflection расширение представл информацию о классах о сво-х

            if ($ref->hasMethod('request')) {//есть ли у него метод request

                if ($ref->isInstantiable()) {//метод вернет true, если можно получить объект класса
                    $class = $ref->newInstance();//создаем объект класса, который пришел в контроллер из класса Route
                    $method = $ref->getMethod('request');//получаем метод об-кт класса ReflectionMethod c именем request
                    $method->invoke($class, $this->get_params());
                    //вызываем метод об-кт класса ReflectionMethod c именем request и
                    // передаем методу имя объа, у которого хитом вызвать метод  request и  массив параметров
                }
            }

        } else {
            throw new ContrException('Такой страницы не существует', 'Контроллер - ' . $this->controller);
        }
    }

    public function init()
    {

        global $conf;

        if (isset($conf['styles'])) {
            foreach ($conf['styles'] as $style) {
                $this->styles[] = trim($style, '/');
            }
        }
        if (isset($conf['styles_admin'])) {
            foreach ($conf['styles_admin'] as $style_admin) {
                $this->styles_admin[] = trim($style_admin, '/');
            }
        }

        if (isset($conf['scripts'])) {
            foreach ($conf['scripts'] as $script) {
                $this->scripts[] = trim($script, '/');
            }
        }

        if (isset($conf['scripts_admin'])) {
            foreach ($conf['scripts_admin'] as $script_admin) {
                $this->scripts_admin[] = trim($script_admin, '/');
            }
        }


    }

    protected function get_controller()
    {
        return $this->controller;
    }

    protected function get_params()
    {
        //action
        return $this->params;
    }

    protected function input()
    {

    }

    protected function output()
    {

    }

    public function request($param = array())
    {
        $this->init();
        $this->input($param);
        $this->output();

        if (!empty($this->error)) {
            $this->write_error($this->error);
        }

        $this->get_page();
    }

    public function get_page()
    {
        echo $this->page;//окончательная точка вывода сгенерированной страницы пользователю
    }

    protected function render($path, $param = array())
    {

        extract($param);

        ob_start();

        if (!include($path . '.php')) {
            //echo $path;
            //exit();
            throw new ContrException('Данного шаблона нет');
        }

        return ob_get_clean();
    }

    public function clear_str($var)
    {

        if (is_array($var)) {
            $row = array();
            foreach ($var as $key => $item) {
                $row[$key] = trim(strip_tags($item));
            }

            return $row;
        }
        return trim(strip_tags($var));
    }

    public function clear_int($var)
    {
        return (int)$var;
    }

    public function is_post()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return TRUE;
        }

        return FALSE;
    }

    public function check_auth()
    {
        try {
            //методы, проверяющие авторизирован ли пользов

            $cookie = Model_User::get_instance();//создаем объект класса Model_User
            //расшифров строку и проверяем
            $cookie->check_id_user();
            $cookie->validate_cookie();//метод сверяет данные куки с дан из config.php
        } catch (AuthException $e) {//$e об-кт класса AuthException

            $this->error = "Ошибка авторизации польз-я |";
            $this->error .= $e->getMessage();//получаем сооб об ошибках

            $this->write_error($this->error);//метод записи ошибок
            header("Location:" . SITE_URL . "login");//переадресация на страницу авторизации
            exit();
        }
    }

    public function write_error($err)//метод формирует ошибку и записывает в файл log.txt
    {
        $time = date("d-m-Y G:i:s");

        $str = "Fault: " . $time . " - " . $err . "\n\r";
        file_put_contents("log.txt", $str, FILE_APPEND);
    }


    public function img_resize($dest,$type) {//уменьшает в размере изображение до задан размер

        switch($type) {
            case 'jpeg':
                $img_id = imageCreateFromJpeg($dest);
                break;
        }

        ///$img_id = imageCreateFromJpeg($dest);

        $img_width = imageSX($img_id);
        $img_height = imageSY($img_id);

      // echo  $img_width."|".$img_height.'<br/>';

       if($img_width/$img_height > 1){//добавила условие, чтобы уменьшать либо по ширину, если она больше
           //либо по высоте, если она больше, ширина больше 1
       $k_w = round($img_width/IMG_WIDTH,2);//подстраиваем картинка в зав-м от высоты, т.к.
            //картинка вертикально вытянута, т.е.изменяем высоту до 175 и ширану изменяется пропорционально,
            // а не ширину до 116 и высота как получится
            $img_mini_width = round($img_width/$k_w);
            $img_mini_height = round($img_height/$k_w);
        }else{//высота меньше 1
            $k_h = round($img_height/IMG_HEIGHT,2);//подстраиваем картинка в зав-м от высоты, т.к.
            //картинка вертикально вытянута, т.е.изменяем высоту до 175 и ширану изменяется пропорционально,
            // а не ширину до 116 и высота как получится

            $img_mini_width = round($img_width/$k_h);
            $img_mini_height = round($img_height/$k_h);
        }


      // echo  $img_mini_width."|".$img_mini_height;//57|175
       // exit();




        $img_dest_id = imageCreateTrueColor($img_mini_width,$img_mini_height);

        $result = imageCopyResampled(
            $img_dest_id,
            $img_id,0
            ,0
            ,0,
            0,
            $img_mini_width,
            $img_mini_height,
            $img_width,
            $img_height
        );
        $name_img = $this->rand_str().'.jpg';

        $img = imageJpeg($img_dest_id,UPLOAD_DIR.$name_img,100);

        imageDestroy($img_id);
        imageDestroy($img_dest_id);

        if($img) {
            return $name_img;
        }
        else {
            return FALSE;
        }
    }

    protected function rand_str() {
        $str = md5(microtime());

        return substr($str,0,10);
    }

}

?>