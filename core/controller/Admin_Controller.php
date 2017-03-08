<?php

class Admin_Controller extends Base_Admin
{//контроллер для редактирования, добавления, удаления
// и вывода на экран страниц сайта
    protected $pages;
    protected $home;//id главной страницы
    //привязываемся к id страницы так как номер id явл неизменным и уникалным в бд
    protected $contacts;

    protected function input($param = array())//метод для подготовки входных данных
    {
        parent::input();
        $this->title .= " Редактирование страниц";

        $this->pages = $this->ob_m->get_pages(TRUE);//получаем массив всех страниц для select
        // print_r($this->pages);
        $home = $this->ob_m->get_home_page();//метод вытасткивает id главной страницы
        //для атрибута selected

        if (is_array($home)) {
            $this->home = $home['page_id'];
        }
//print_r($this->home);

        $contacts = $this->ob_m->get_contacts();//метод вытасткивает id главной страницы
        //для атрибута selected

        if (is_array($contacts)) {
            $this->contacts = $contacts['page_id'];
        }
        //print_r($this->contacts);
    }

    protected function output()//возвращ польностью готовую страницу для вывода
    {
        $this->content = $this->render(VIEW . 'admin/edit_pages', array(
            'pages' => $this->pages,
            'home' => $this->home,
            'contacts' => $this->contacts
        ));

        $this->page = parent::output();//в свойство $this->page сохраняем отработку метода


        return $this->page;

    }

}















