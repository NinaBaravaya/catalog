<?php
defined('PROM') or exit('Access denied');
//класс по шаблону singleton
class Model_User{
    static $instance;
    static $cookie_name = 'USERNAME';//имя файла куки

    protected $ins_driver_u;//в данное сво-во б сохранять об-кт класса Model_Driver
    protected $user_id;//сво-ство хранит id пользователя
 protected $glue = "|";//склеиватель массива

////////шифров///////
    private $td;//дескриптор открытого модуля ecrypt
    private $cyfer = MCRYPT_BLOWFISH;//алгоритм шированиия
    private $mode = MCRYPT_MODE_CFB;//режим шифрования
    private $created;//время создания куки
    private $version;//версия куки
/////////шифров/////////

    static function get_instance(){
        if(self::$instance instanceof self){//проверяем,если в свойстве $instance,записам объект класса Model
            return self::$instance;//то вернем объект класса Model
        }else{//если нет, то создадим объект класса Model
            return self::$instance = new self;
        }
    }

    private function __construct()
    {
   $this->ins_driver_u = Model_Driver::get_instance();//получаем об-кт класса


       $this->td =  mcrypt_module_open($this->cyfer,'',$this->mode,'');//открываем модуль шифрования

    }
    public function get_user($name,$password){
        $result = $this->ins_driver_u->select(
            array('user_id'),
            'users',
            array('login'=>$name,
                'password'=>md5($password))
        );
        if($result == NULL || $result == FALSE){
            throw new AuthException('Пользователь с такими данными не найден');
            //данное исключение будет генерироваться если пользователь ввел неправильно логин или пароль
        return;
        }
        if(is_array($result)){
            return $result[0]['user_id'];
        }
    }

    public function get_fealtures($ip){//выташим кол-во неправильных попыток для конкретного ip
        $result = $this->ins_driver_u->select(
            array('fealtures'),
            'fealtures',
            array('ip'=>$ip)
        );
        if(count($result) == 0){
         return NULL;
        }
        return $result[0]['fealtures'];
    }

    public function insert_fealt($ip){//метод впишет 1 при неправильно введ данных пользователем
        $this->ins_driver_u->insert(//$this->ins_driver_u - объект класса Model_Driver
            'fealtures',
            array('fealtures','ip','time'),
            array('1',$ip,time())
        );
    }

    public function update_fealt($ip,$fealtures){
        $fealtures++;
        $this->ins_driver_u->update(
            'fealtures',
            array('fealtures','time'),
            array($fealtures,time()),
            array('ip'=>$ip)
        );

    }

    public function clean_fealtures($time){
        $this->ins_driver_u->delete(
            'fealtures',
            array('time'=>$time),
            array('<=')
        );
    }

    public function check_id_user($id = FALSE){//id авториз польз
      if($id){//если id польз передан, то запишем его в сво-во $this->user_id

          return $this->user_id = $id;
      }else{
          //если id не передан, то попытаемся его вытащить из файла куки
          if(array_key_exists(self::$cookie_name,$_COOKIE)){
              //а сущ ли куки
              //метод расширует файл куки
              $this->unpackage($_COOKIE[self::$cookie_name]);//передадим методу статич сво-во
          }else{
              //если файла куки нет, зн поль не авторизован
              throw new AuthException('Доступ запрещен');
          }
      }
    }

    public function set(){//метод устанавливает куки, т.е. записывает определенную строку в файл
    $cookie_text = $this->package();//метод вернет зафрован строку
       if($cookie_text){
       setcookie(self::$cookie_name,$cookie_text,0,SITE_URL);
           //такая конструкция для вызова статического сво-ва
       return TRUE;
       }
    }

    private function package(){//формирование строки для шифрования
        if($this->user_id){//еслм ест ьid польз
          $arr= array(
              $this->user_id,
              time(),
              VERSION
          );//массив данных для файла кук

            $str = implode($this->glue,$arr);
            return $this->encrypt($str);//шифруем данные
            //echo $str;
        }else{
            throw new AuthException("Не найден id пользователя");
        }
    }

    private function encrypt($str){//шифрование строки метод private вызываем толькл внутри модели
       //создаем вектор инициализации
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->td),MCRYPT_RAND);
        //открываем буфер обмена
        mcrypt_generic_init($this->td,KEY,$iv);
        //шифруем текст
        $crypt_text = mcrypt_generic($this->td,$str);
        //закрываем буфер обмена
        mcrypt_generic_deinit($this->td);

        return $iv.$crypt_text;//возвращаем зашифров строку
    }

    private function unpackage($str) {
        $result = $this->decrypt($str);
        //echo $result;//1|1487053003|110

        //создадим переменные из массива с пом list
        list($this->user_id,$this->created,$this->version) = explode($this->glue,$result);
        true;
    }

    private function decrypt($str) {//метод расшифровывает строку
        $iv_size = mcrypt_enc_get_iv_size($this->td);//длина вектора инициализации
        $iv = substr($str,0,$iv_size);
        $crypt_text = substr($str,$iv_size);

        mcrypt_generic_init($this->td,KEY,$iv);//открываем буфер обмена

        $text = mdecrypt_generic($this->td,$crypt_text);//расшифровываем данные

        mcrypt_generic_deinit($this->td);//закрываем буфер обмена

        return $text;//возвращаем расшир строку
    }

    public function validate_cookie() {//сверяем куки
        //$this->user_id = FALSE;//тогда перейдем на стр авторизации
        if(!$this->user_id || !$this->version || !$this->created) {
            throw new AuthException("Не правильные данные. Доступ запрещен");
        }

        if($this->version != VERSION) {//соотв ли версия куки консткнте
            throw new AuthException("НЕ правильная версия файла кук");
        }

        if((time() - $this->created) > EXPIRATION) {//если прошло 10 минут от момента установки кук
            throw new AuthException("Закончилось время сессии");
        }
        if((time() - $this->created) > WARNING_TIME) {//обновление файла кук, чтобы устанавливалось новое время установки кук
            $this->set();//устанавливаем заново куки
        }

        return TRUE;
    }

    public function logout() {//разлогинивание пользователя - очищаем куки
        setcookie(self::$cookie_name,"",(time()-3600),SITE_URL);
        return TRUE;
    }
}





























