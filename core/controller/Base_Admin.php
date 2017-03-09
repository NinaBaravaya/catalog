<?php
abstract class Base_Admin extends Base_Controller{
    protected $ob_m;//в данном свойстве будем хранить объект модели сайта
   protected $ob_us;//Объект модели класса Model_User
    protected $brands;
    protected $title;//сво-во хранит заголовок страниц
    protected $style;
    protected $script;
    protected $content;//сгенериров шаблон центр+ прав часть
    protected $user = TRUE;//нужно ли авторизиров пользователям на сайте


    protected function input()//метод для подготовки входных данных
    {
        if($this->user == TRUE){//если нужна авторизация пользователя,
            // то вызываем метод >check_auth(), чтобы проверить авторизировался ли пользователь
            $this->check_auth();
        }
       $this->title .= "Каталог бытовой техники |";
        foreach($this->styles_admin as $style){
            $this->style[] = SITE_URL.VIEW.'admin/'.$style;
        }
        foreach($this->scripts_admin as $script){
            $this->script[] = SITE_URL.VIEW.'admin/'.$script;
        }
        $this->ob_m = Model::get_instance();//получим об-к класса
        $this->ob_us = Model_User::get_instance();//получим об-к класса
        $this->brands = $this->ob_m->get_catalog_brands();
    }
    protected function output()//возвращ польностью готовую страницу для вывода
    {
        //сгенерируем шаблоны для вывода статических областей сайта
        $header = $this->render(VIEW.'admin/header', array(
                                             'title'=>$this->title,
                                              'styles'=>$this->style,
                                              'scripts'=>$this->script
        ));

        $left_bar = $this->render(VIEW.'admin/left_bar',array('brands' => $this->brands,));//параметры не нужны так как выводится статическое меню

        $footer=$this->render(VIEW.'admin/footer');

        //генерируем весь полный шаблон
        $page = $this->render(VIEW.'admin/index',array(
            'header'=>$header,
            'left_bar'=>$left_bar,
            'content'=>$this->content,//заполняется в дочерних классах дан класса
            'footer'=>$footer
        ));

        return $page;//готовая страница
    }
}






























