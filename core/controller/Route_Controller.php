<?php

defined('PROM') or exit('Access denied');

class Route_Controller extends Base_Controller {

    static $_instance;

    static function get_instance() {

        if(self::$_instance instanceof self) {
            return self::$_instance;
        }

        return self::$_instance = new self;
    }


    private function __construct() {

        $zapros = $_SERVER['REQUEST_URI'];

        $path = substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],'index.php'));

        if($path === SITE_URL) {
            $this->request_url = substr($zapros,strlen(SITE_URL));

            $url = explode('/',rtrim($this->request_url,'/'));


                if (!empty($url[0])) {
                $this->controller = ucfirst($url[0]).'_Controller';
            }
            else {
                $this->controller = "Catalog_Controller";
            }

            $count = count($url);

            if(!empty($url[1])) {

                $key = array();
                $value = array();

                for($i = 1;$i < $count; $i++) {

                    if($i%2 != 0) {
                        $key[] = $url[$i];
                    }
                    else {
                        $value[] = $url[$i];
                    }
                }

                if(!$this->params = array_combine($key,$value)) {
                    throw new ContrException("Не правильный адресс",$zapros);
                }
            }
        }
        else {
            try{
                throw new Exception('<p style="color:red">Не правильный адресс сайта.</p>');
            }
            catch(Exception $e) {
                echo $e->getMessage();
                exit();
            }
        }

    }
}
?>