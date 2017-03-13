<?php
defined('PROM') or exit('Access denied');
class Error_Controller extends Base_Error {//обрабатывает входящие данные об ошибках
// выводит сообщения об ошибке на экран

    protected function input($param = array()) {//параметры из ContrException либо DbException
        parent::input();

        $er = '';//строка для записи в файл логов
        $arr = array();
        if(isset($param['mes'])) {//цикл здесь для универсльности, если захочешь передавать еще параметры
            foreach($param as $key=>$val) {
                $val = $this->clear_str(rawurldecode($val));//строк расшифровываем, очишаем
                $arr[] = $val;//строка для выводва в шаблоне error_page

                $er .= $key.' - '.$val.'|';//строка для записи в файл

            }

            if($_SESSION['error']) {
                foreach($_SESSION['error'] as $k=>$v) {
                    $er .= $k.' - '.$v.'|';
                }
            }
            //unset($_SESSION['error']);//если удалять сессионную переменную, то после обновения не будет передана ячейка error
            $this->error = $er;//записывает значение в сво-во для записи ошибки в log.txt
            $this->message_err = $arr;//записывает значение в сво-во для вывода на экран
        }
    }

    protected function output() {
        $this->page = parent::output();

        return $this->page;
    }
}
?>