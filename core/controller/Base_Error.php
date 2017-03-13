<?php
defined('PROM') or exit('Access denied');
abstract class Base_Error extends Base_Controller {//выводит сообщения об ошибке на экран
//класс занимается выводом полного шаблона на экран (как Base_Controller)
    protected $message_err;//массив для вывода на экран записывает Error_Controller
    protected $title;

    protected function input() {
        $this->title = 'Страница показа ошибок';
    }

    protected function output() {

        $page = $this->render(VIEW.'error_page',array(
                'title' => $this->title,
                'error' => $this->message_err
            ));
        return $page;
    }
}
?>