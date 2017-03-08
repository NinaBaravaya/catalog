<?php
class Editcatalog_Controller extends Base_Admin {

    protected $option = 'view';
    protected $brands;
    protected $id;
    protected $catalog;
    protected $navigation;
    protected $message;
    protected $type;
    protected $type_cat;
    protected $tovar;//массив дан по выбр товару
    protected $tovar_id;//id товара
    protected $type_img;//тии изображения

    protected function input($param = array()) {
        parent::input();
        $this->title .= " Админка - каталог";
        $this->brands = $this->ob_m->get_catalog_brands();

        if(isset($param['page'])) {
            $page = $this->clear_int($param['page']);
            if(!$page) {
                $page = 1;
            }
        }
        else {
            $page = 1;
        }

        if(isset($param['brand'])) {
            $this->id = $this->clear_int($param['brand']);
            $this->type = 'brand';

            $pager = new Pager(
                $page,
                'tovar',
                array('brand_id'=>$this->id),
                'tovar_id',
                'ASC',
                QUANTITY,
                QUANTITY_LINKS
            );
            if(is_object($pager)) {
                $this->catalog = $pager->get_posts();
                $this->navigation = $pager->get_navigation();
            }
        }
        elseif(isset($param['parent'])) {
            $this->type = 'parent';
            $this->id = $this->clear_int($param['parent']);

            if(!$this->id) {
                return;
            }
            $ids = $this->ob_m->get_child($this->id);
            if(!$ids) {
                return;
            }
            $pager = new Pager(
                $page,
                'tovar',
                array('brand_id' => $ids),
                'tovar_id',
                'ASC',
                QUANTITY,
                QUANTITY_LINKS,
                array("IN")
            );
            if(is_object($pager)) {
                $this->catalog = $pager->get_posts();
                $this->navigation = $pager->get_navigation();
            }
        }

        if($param['option'] == 'add') {
            $this->option = 'add';
            $this->type_cat = $this->ob_m->get_catalog_type();

            if($param['id']) {//идентификатор категории
                $this->id = $this->clear_int($param['id']);
            }
        }

       if($param['option'] == 'edit') {
            $this->type_cat = $this->ob_m->get_catalog_type();
            if($param['tovar']) {//проверяем пришел ли id товара
                $this->tovar_id = $this->clear_int($param['tovar']);
                $this->tovar = $this->ob_m->get_tovar_adm($this->tovar_id);
                $this->option = 'edit';

                //print_r($this->tovar);
            }
        }

        if($param['option'] == 'delete') {//УДАЛЕНИЕ ТОВАРА
            if($param['tovar']) {//есть id товара
                $this->tovar_id = $this->clear_int($param['tovar']);

                $result = $this->ob_m->delete_tovar($this->tovar_id);

                if($result === TRUE) {
                    $_SESSION['message'] = "Товар успешно удален";
                }
                else {
                    $_SESSION['message'] = "Ошибка удаления товара";
                }

                if(array_key_exists('brand',$param)) {//то значит пользователь удалял товар из дочерней категории
                    header("Location:".SITE_URL.'editcatalog/brand/'.$param['brand']);
                }
                elseif(array_key_exists('parent',$param)) {//то значит пользователь удалял товар из родительской категории
                    header("Location:".SITE_URL.'editcatalog/parent/'.$param['parent']);
                }

                exit();
            }
        }

        if($this->is_post()) {

            $id = $this->clear_int($_POST['id']);
            $title = $_POST['title'];
            $anons = $_POST['anons'];
            $text = $_POST['text'];
            $type = $_POST['type'];
            $new_type = $_POST['new_type'];
            $publish = $_POST['publish'];
            $price = $this->clear_int($_POST['price']);
            $keywords = $_POST['keywords'];
            $description = $_POST['description'];
            $category = $_POST['category'];//тип категории товара

            if(!empty($title) && !empty($anons) && !empty($text) && !empty($price)) {


                if(empty($_FILES['img']['tmp_name'])) {
                    $img = NOIMAGE;
                }
                else {

                    if(!empty($_FILES['img']['error'])) {
                        $_SESSION['message'] = "Слишком большое изображение";
                        header("Location:".SITE_URL.'editcatalog/option/add/id/'.$this->id);
                        exit();
                    }

                    $img_types = array('jpeg' => 'image/jpeg');
                    $this->type_img = array_search($_FILES['img']['type'],$img_types);
                    if(!$this->type_img) {
                        $_SESSION['message'] = "Не правильный формат изображения";
                        header("Location:".SITE_URL.'editcatalog/option/add/id/'.$this->id);
                        exit();
                    }

                    if($_FILES['img']['size'] > (2 * 1024* 1024)) {
                        $_SESSION['message'] = "Слишком большое изображение";
                        header("Location:".SITE_URL.'editcatalog/option/add/id/'.$this->id);
                        exit();
                    }

                    if(!move_uploaded_file($_FILES['img']['tmp_name'],UPLOAD_DIR.$_FILES['img']['name'])) {

                        $_SESSION['message'] = "Ошибка копиррования изображения";
                        header("Location:".SITE_URL.'editcatalog');
                        exit();
                    }
                    $res_img = $this->img_resize(UPLOAD_DIR.$_FILES['img']['name'],$this->type_img);

                    if($res_img !== FALSE) {
                        $img = $res_img;
                        unlink(UPLOAD_DIR.$_FILES['img']['name']);
                    }
                    else {
                        $_SESSION['message'] = "Ошибка изменения размера изображения";
                        header("Location:".SITE_URL.'editcatalog/option/add/id/'.$this->id);
                        exit();
                    }

                }
                if($this->option == 'add') {
                    if(!empty($new_type)) {
                        $type = $this->ob_m->add_new_type($new_type);
                    }
                    $result = $this->ob_m->add_goods(
                        $this->id,
                        $title,
                        $anons,
                        $text,
                        $img,
                        $type,
                        $publish,
                        $price,
                        $keywords,
                        $description
                    );
                    if($result === TRUE) {
                        $_SESSION['message'] = "Товар успешно добавлен";
                    }
                    else {
                        $_SESSION['message'] = "Ошибка добавления товара";
                    }
                    header("Location:".SITE_URL.'editcatalog');
                    exit();
                }

                if($this->option = 'edit') {
                    if(!empty($new_type)) {
                        $type = $this->ob_m->add_new_type($new_type);//метод добавит новый тип в бд
                    }

                    if($img == NOIMAGE) {
                        $img = FALSE;//то ничего записывать в поле не будем
                    }
                    $result = $this->ob_m->edit_goods(
                        $id,
                        $title,
                        $anons,
                        $text,
                        $img,
                        $type,
                        $publish,
                        $price,
                        $category,
                        $keywords,
                        $description
                    );
                    if($result === TRUE) {
                        $_SESSION['message'] = "Изменения успешно выполнены";
                    }
                    else {
                        $_SESSION['message'] = "Ошибка изменения товара";
                    }
                    header("Location:".SITE_URL.'editcatalog');
                    exit();
                }
            }
            else {
                $_SESSION['message'] = "Заполните обязательные поля(заголовок, анонс, полное описание и цена)";

                header("Location:".SITE_URL.'editcatalog/option/add/id/'.$this->id);
                exit();
            }
        }

        $this->message = $_SESSION['message'];
    }

    protected function output() {

        $previous = '/'.$this->type.'/'.$this->id;

        $this->content = $this->render(VIEW.'admin/edit_catalog',array(
            'option' => $this->option,
            'brands' => $this->brands,
            'category'=>$this->id,
            'goods' => $this->catalog,
            'navigation'=>$this->navigation,
            'previous'=>$previous,
            'mes'=>$this->message,
            'type_cat' => $this->type_cat,
            'tovar' => $this->tovar
        ));

        $this->page = parent::output();
        unset($_SESSION['message']);
        return $this->page;

    }
}
?>