<?php
class Login_Controller extends Base{

    protected $ob_us;
//данный контроллер является наследником класса Base,чтобы все пользователи имели доступ к странице авторизации
//как привязать данную страницу админ панели
protected function input($param = array())
{
    parent::input();



    $this->ob_us = Model_User::get_instance();//получим об-кт класса

    if(isset($param['logout'])){
        $logout = $this->clear_int($param['logout']);

        if($logout){
            $res = $this->ob_us->logout();
            if($res){
                header("Location:".SITE_URL);
                exit();
            }
        }
    }

    $time_clean = time() - (600*24*FEALT);//по прошествии указанного времени пользователя н разблокировать
    $this->ob_us->clean_fealtures($time_clean);//метод "разбанивает" пользователя


    $ip_u = $_SERVER['REMOTE_ADDR'];//сохраняем ip адресс пользователя
    $fealtures = $this->ob_us->get_fealtures($ip_u);//кол-во нерпавильных  попыток для данного пользователя


    //в первую очередь сделаем проверку: пришли ли данные методом post
    if($this->is_post()){


        if(isset($_POST['name']) && isset($_POST['password']) && $fealtures < 3){//заполнил ли пользователь логин и парол


            //var_dump($fealtures);


            $name = $this->clear_str($_POST['name']);
        $password = $this->clear_str($_POST['password']);
            //сущ ли в бд такой пользователь
            //и вернем id пользователя для файла куки

            try{//проверка
            $id = $this->ob_us->get_user($name, $password);//если пользователь не найден
              $this->ob_us->check_id_user($id);//параметр - id авторизированного пользователя
                //echo $id;
////////////////////////////////
                //формируем строку и записыв ее в шифров виде в файл куки
                $this->ob_us->set();//устанавливает куки (записывает определ строку в файл куки)
                ///////////////////////
               //метод будет записывать в куки строку определенного формата
               header("Location:".SITE_URL.'editcatalog');
                exit();

            }catch(AuthException $e){//перехват исключ

            if($fealtures == NULL){//зн польз еще не вводил неправильных данных
                $this->ob_us->insert_fealt($ip_u);
            }
                elseif($fealtures > 0){//в данном случае нам нужно обновит ьзначение поля fealtures
                    $this->ob_us->update_fealt($ip_u,$fealtures);//обновит поле  $fealtures  для конкретного ip
                }
            }
        }
    }
}
    protected function output()
    {
        //$this->content = $this->render(VIEW.'login_page',array('error'=>$_SESSION['auth']));
        $this->content = $this->render(VIEW.'login_page',array('error'=>$_SESSION['auth']));
        $this->page = parent::output();
        unset($_SESSION['auth']);
        return $this->page;
    }
}
