<?php

class Editcategory_Controller extends Base_Admin
{
    protected $brands;//массив категорий (древовидный массив справа)
    protected $parents_cat;//массив родительсикх категорий
    protected $message;//массив системных сообщений
    protected $option = 'add';//действие, которое должен выполнит ьконтроллер
    protected $id;//id категории, которую мы выбрали для редактирования
    protected $category;//массив данных категории

    protected function input($param = array())//метод для подготовки входных данных
    {
        parent::input();

        $this->title .= "Админка - Категории";

        if ($param['option'] == 'edit') {//редактирование категории
            $this->option = 'edit';
            if ($param['id']) {//id категории
                $this->id = $this->clear_int($param['id']);//записываем $id в сво-во this->$id
                //вызываем метод get_category только если получили id категории
                $this->category = $this->ob_m->get_category($this->id);//массив данных по категории

            }
        }
        if ($param['option'] == 'delete') {//удаление категории
            if ($param['id']) {//если в массиве id категории
                $id = $this->clear_int($param['id']);
                if ($id) {
                    $result = $this->ob_m->delete_category($id);

                    if ($result === TRUE) {
                        $_SESSION['message'] = "Категория успешно удалена";
                    } else {
                        $_SESSION['message'] = "Ошибка удаления категории";
                    }

                    header("Location:" . SITE_URL . 'editcatalog');
                    exit();
                }
            }
        }
        //пришли ли данные методом POST
        if ($this->is_post()) {
            $title = $_POST['title'];
            $parent = $_POST['parent'];
            $id = $this->clear_int($_POST['id']);//получаем id категории из поля hidden для редиректа
            //добавл данные
            if ($this->option == 'add') {//ДОБАВЛЕНИЕ КАТЕГОРИИ
                //если была нажатакнопка отправки данных
                if (empty($title)) {
                    $_SESSION['message'] = "Заполните заголовок категории";
                    header("Location:" . SITE_URL . 'editcategory/option/add');
                    exit();
                }
                //теперь вызовем метод модели, который и добавит данные в бд
                $result = $this->ob_m->add_category($title, $parent);//метод добавит данные в бд
                if ($result === TRUE) {
                    $_SESSION['message'] = "Категория добавлена";
                } else {
                    $_SESSION['message'] = "Категория НЕ добавлена";
                }
                header("Location:" . SITE_URL . 'editcategory/option/add');
                exit();
            }
            if ($this->option == 'edit') {//РЕДАКТИРОВАНИЕ КАТЕГОРИИ
                if (empty($title)) {//если пользователь не ввел название категории
                    $_SESSION['message'] = "Заполните заголовок категории";
                    header("Location:" . SITE_URL . 'editcategory/option/edit/id/'.$id);
                    exit();

                } else {
                    $result = $this->ob_m->edit_category($title,$parent,$id);//метод редактирует категорию в бд

                    if ($result === TRUE) {
                        $_SESSION['message'] = "Категория изменена";
                    } else {
                        $_SESSION['message'] = "Категория НЕ изменена";
                    }
                    header("Location:" . SITE_URL . 'editcategory/option/edit/id/'.$id);
                    exit();
                }


            }
        }

        $this->brands = $this->ob_m->get_catalog_brands();//массив родит и дочерних категорий
        $this->parents_cat = $this->ob_m->get_parent_brands();//метод возвращ родит категории
        //print_r( $this->parents_cat);

        $this->message = $_SESSION['message'];

    }

    protected function output()//возвращ польностью готовую страницу для вывода
    {
        $this->content = $this->render(VIEW . 'admin/edit_category', array(
            'brands' => $this->brands,
            'parents_cat' => $this->parents_cat,
            'mes' => $this->message,
            'option' => $this->option,
            'category' => $this->category
        ));
        $this->page = parent::output();
        unset($_SESSION['message']);//удалим сессион перем
        return $this->page;
    }
}


















