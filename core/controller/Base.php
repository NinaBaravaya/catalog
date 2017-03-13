<?php
defined('PROM') or exit('Access denied');
abstract class Base extends Base_Controller{
    protected $ob_m;//объект модели сайта
    protected $title;//заголовок каждой страницы
    protected $style;//стили,которые необходимо подключить к сайту
    protected $script;//готовые пути для скриптов
    protected $header;//для хранения сгенерированного шаблона шапки сайта
    protected $header_menu;//верхнее меню, сохраним отработку метода, который вернет меню
    protected $content;//средняя часть сайта
    protected $left_bar;//свойство ля хранения левого блока
    protected $right_bar;//для хранения правого блока
    protected $footer;

    protected $catalog_brands,$keywords,$description;//свойство хранит массив категорий, котор. выбрали из бд

    protected function input(){//метод формирует входные параметры для генерации страницы
    $this->title = "Каталог бытовой техники | ";

        foreach($this->styles as $style){//подключаем стили к странице
            $this->style[] = SITE_URL.VIEW.$style;
        }
        foreach($this->scripts as $script){//подключаем скрипты к странице
            $this->script[] = SITE_URL.VIEW.$script;
        }

        //получение Object Model
        $this->ob_m = Model::get_instance();
        $this->catalog_brands = $this->ob_m->get_catalog_brands();

    }
    protected function output(){//метод генерирует шаблон и выводит его на экран
            $this->left_bar = $this->render(VIEW.'left_bar',array(
                    'brands'=>$this->catalog_brands
                )
            );

        $this->footer = $this->render(VIEW.'footer',array(
        ));

        $this->header = $this->render(VIEW.'header', array(
            'styles'=>$this->style,
            'scripts'=>$this->script,
            'title'=>$this->title,
            'keywords'=>$this->keywords,
            'description'=>$this->description,

        ));//указываем путь к шаблону шапки сайта

        $page = $this->render(VIEW.'index',array('header' => $this->header,
                                                  'left_bar' => $this->left_bar,
                                                  'content' => $this->content,
                                                  'footer' => $this->footer
                                                 ));
        return $page;//возвращаем полностью готовую страницу
    }
}












