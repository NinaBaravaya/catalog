<?php
//так как объект класса нам не нужен пишем слово abstract
abstract class Base extends Base_Controller{
    protected $ob_m;//объект модели нашего сайта
    protected $title;//заголовок каждой страницы
    protected $style;//стили,которые необходимо подключить к сайту
    protected $script;//готовые пути для скриптов
    protected $header;//для хранения сгенерированного шаблона шапки сайта
    protected $header_menu;//верхнее меню, сохраним отработку метода, который вернет меню
    protected $content;//средняя часть сайта
    protected $left_bar;//свойство ля хранения левого блока
    protected $right_bar;//для хранения правого блока
    protected $footer;
   // protected $need_right_side = FALSE;//нужна ли правая часть сайта
    protected $home_page = FALSE;//признак того, что страница главная

    protected $news,$pages,$catalog_type,$catalog_brands,$keywords,$description;//свойство хранит массив новостей, котор. выбрали из бд
    //$pages свойство хранит массив страниц, котор. выбрали из бд



    protected function input(){//метод формирует входные параметры для генерации страницы
    $this->title = "Каталог бытовой техники | ";

        foreach($this->styles as $style){//подключаем стили к странице
            $this->style[] = SITE_URL.VIEW.$style;
        }
        //print_r($this->style);//Array ( [0] => /template/default/style.css )
        foreach($this->scripts as $script){//подключаем стили к странице
            $this->script[] = SITE_URL.VIEW.$script;
        }
        //print_r($this->script);

        //получение Object Model
        $this->ob_m = Model::get_instance();
        $this->news = $this->ob_m->get_news();
        $this->pages = $this->ob_m->get_pages();
        $this->catalog_type = $this->ob_m->get_catalog_type();
        $this->catalog_brands = $this->ob_m->get_catalog_brands();

        $this->header_menu = $this->ob_m->get_header_menu();

    }
    protected function output(){//метод генерирует шаблон и выводит его на экран

        if($this->home_page)
            $this->left_bar = $this->render(VIEW.'left_bar',array(

                    'brands'=>$this->catalog_brands
                )
            );



        $this->footer = $this->render(VIEW.'footer',array(
                                                 // 'pages'=>$this->pages
            //'home_page'=>$this->home_page
        ));



        $this->header = $this->render(VIEW.'header', array(
            'styles'=>$this->style,
            'scripts'=>$this->script,
            'title'=>$this->title,
            'keywords'=>$this->keywords,
            'description'=>$this->description,
            'pages'=>$this->pages,
        ));//указываем путь к шаблону шапки сайта



        $page = $this->render(VIEW.'index',array('header' => $this->header,

                                                  'left_bar' => $this->left_bar,
                                                  'content' => $this->content,
                                                  /*'right_bar' => $this->right_bar,*/
                                                  'footer' => $this->footer
                                                 ));
        return $page;//возвращаем полностью готовую страницу
    }
}












