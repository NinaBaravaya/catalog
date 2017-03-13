<?php
defined('PROM') or exit('Access denied');

class Tovar_Controller extends Base
{
    protected $tovar;//в данном свойстве мы будем хранить массив полного описания товара
    protected $krohi; //массив хлебн крошек

    protected function input($param = array())//параметры запроса 'id/1'
    {
        parent::input();//вызываем родительский метод класса Base
        $this->home_page = TRUE;
        //чтобы получить данные по товару, нужно получить id
        if (isset($param['id'])) {
            $id = $this->clear_int($param['id']);//очистим дан числ типа
            if ($id) {
                $this->tovar = $this->ob_m->get_tovar($id);//обращаемся к об-у класса model -
                // print_r( $this->tovar);
                $this->title .= $this->tovar['title'];
                $this->keywords = $this->tovar['keywords'];
                $this->description = $this->tovar['description'];
                $this->krohi[0]['tovar_name'] = $this->tovar['title'];
            }
        }
    }

    protected function output()
    {
        $this->content = $this->render(VIEW . 'tovar_page', array(
            'tovar' => $this->tovar,
            'krohi' => $this->krohi
        ));
        $this->page = parent::output();
        return $this->page;
    }
}