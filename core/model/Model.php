<?php

class Model
{
    //сделаем данный класс по шаблону проектирования Singleton
    //чтобы исключить создание множества объектов у одного класса
    static $instance;

    public $ins_driver;//свойство будет хранить объект класса Model_Driver
    //свойство публичное,чтобы мы могли иметь к нему доступ из модели Model

    //
    static function get_instance()
    {
        if (self::$instance instanceof self) {//проверяем,если в свойстве $instance,записам объект класса model
            return self::$instance;//то вернем объект класса Model
        } else {//если нет, то создадим объект класса Model
            return self::$instance = new self;
        }
    }

    //создадим констрктор вызовется автоматически при создании объекта класса

    private function __construct()
    {
        //логичнее всего при создании объекта выполнить подключение к бд
        //поэтому создадим объект класса model_driver, а он подключиться к бд

        try {//делаем обработку ошибок при соединени с бд
            $this->ins_driver = Model_Driver::get_instance();//обратимся к свойству ins_driver
            //и сохраним в него объект класса Model_Driver

        } catch (DbException $e) {
            exit();
        }


    }

 /*   public function get_news()
    {//мтеод публичный т.к. доступ к нему должен быть открыт из других классов
        //$result - в переменной хнаится массив новостей, выбран из бд
        //$this->ins_driver - свойтсво, в котором хранится объект класса Model_Driver
        //обращаемся к методу select, чтобы выбрать нужные поля из бд
        $result = $this->ins_driver->select(
            array('news_id', 'title', 'anons', 'date'),
            'news',
            array(),
            'date',
            'DESC',
            3
        );
        $row = array();
        foreach ($result as $value) {//распаковали массив
            $value['anons'] = substr($value['anons'], 0, 255);//поработали с конкретной ячейкой
            $value['anons'] = substr($value['anons'], 0, strrpos($value['anons'], ' '));
            $row[] = $value;//запаковали обрезанные ячейки в массив
        }
        return $row;
    }

    public function get_pages($all = FALSE)
    {//метод создаст массив страниц, котор вытащит из бд

        if ($all) {
            $result = $this->ins_driver->select(
                array('page_id', 'title', 'type'),
                'pages',
                array(),//WHERE type ('post','contacts')
                'position',
                'ASC'
            );
        } else {
            $result = $this->ins_driver->select(
                array('page_id', 'title'),
                'pages',
                array('type' => "'post','contacts'"),//WHERE type ('post','contacts')
                'position',
                'ASC',
                FALSE,
                array("IN")//операнд IN
            );
        }
        return $result;
        // echo $result;
    }*/

    public function get_catalog_type()
    {
        $result = $this->ins_driver->select(//метод создаст массив каталога, котор вытащит из бд
            array('type_id', 'type_name'),
            'type'
        );
        return $result;
    }

    public function get_catalog_brands()
    {//метод получит производителей товаров
        $result = $this->ins_driver->select(
            array('brand_id', 'brand_name', 'parent_id'),
            'brands'
        );
        $arr = array();
        foreach ($result as $item) {
            if ($item['parent_id'] == 0) {//родит категория
                $arr[$item['brand_id']][] = $item['brand_name'];//массив с родит категориями
            } else {
                //Дочерняя
                $arr[$item['parent_id']]['next_lvl'][$item['brand_id']] = $item['brand_name'];//ключ массива id родит категории
                //дописываем в родит ячейку дочернююкаетгорию с ключом next_lvl
            }
        }
        return $arr;
    }

 /*   public function get_home_page()
    {//метод вернет главную страницу сайта
        $result = $this->ins_driver->select(
            array('page_id', 'title', 'text', 'keywords', 'description'),
            'pages',
            array('type' => 'home'),//where type=home
            FALSE,
            FALSE,
            1
        );
        return $result[0];//выбираем из бд данные о странице главная
    }

    public function get_header_menu()
    {//метод будет доставать типы из бд для header
        $result = $this->ins_driver->select(
            array('type_id', 'type_name'),
            'type',
            array('in_header' => "'1','2','3','4'"),//WHERE type IN(1,2,3,4)
            'in_header',
            'ASC',
            4,
            array('IN')

        );
        $row = array();
        foreach ($result as $item) {
            $item['type_name'] = str_replace(" ", "<br />", $item['type_name']);
            $item['type_name'] = mb_convert_case($item['type_name'], MB_CASE_UPPER, "UTF-8");

            $row[] = $item;
        }
        return $row;
    }


    public function get_news_text($id)
    {
        $result = $this->ins_driver->select(//$this->ins_driver - обращаемся к об-у класса
            array('title', 'text', 'date', 'keywords', 'description'),
            'news',
            array('news_id' => $id)
        );
        return $result[0];
    }

    public function get_page($id)
    {
        $result = $this->ins_driver->select(//$this->ins_driver//сво-во хрант об-т класса Model_Driver
            array('title', 'keywords', 'description', 'text'),
            'pages',
            array('page_id' => $id)
        );
        return $result[0];
    }

    public function get_contacts()
    {
        $result = $this->ins_driver->select(
            array('page_id', 'title', 'text', 'keywords', 'description'),
            'pages',
            array('type' => 'contacts')
        );
        return $result[0];
    }*/


    public function get_child($id)
    {//метод вернет все товары дочерних категорий
        $result = $this->ins_driver->select(
            array('brand_id'),
            'brands',
            array('parent_id' => $id)//условие фильтрации
        );

        if ($result) {
            $row = array();
            foreach ($result as $item) {//ошибка появлялась так как $result == FALSE
                $row[] = $item['brand_id'];

            }
            $row[] = $id;

            //склеим массив дочерних категорий в строку
            $res = implode(",", $row);

            return $res;
        } else {
            return FALSE;
        }

    }


//метод будет работать напрямую с классом model_driver
    public function get_krohi($type, $id)
    {
        if ($type == 'type') {//если мы работаем с товарами по назначению
            $sql = "SELECT type_id, type_name
              FROM type
              WHERE type_id = $id";
        }
        if ($type == 'brand') {//если мы работаем с товарами по назначению
            //во втором подзапросе вытаскиваем родит категорию у этой дочерней категории
            $sql = "(SELECT brand_id, brand_name
              FROM brands
              WHERE brand_id = (SELECT parent_id
              FROM brands
              WHERE brand_id = $id))

               UNION

              (SELECT brand_id, brand_name
              FROM brands
              WHERE brand_id = $id)";
        }
        $result = $this->ins_driver->ins_db->query($sql);//сво-во ins_db принадлежит расширению mysqli
        if (!$result) {
            throw new DbException("Ошибка базы данных" . $this->ins_driver->ins_db->errno . "|" .
                $this->ins_driver->ins_db->error);

        }
        if ($result->num_rows == 0) {//$result - объект выборки данных из бд
            return FALSE;
        }
        $row = array();
        for ($i = 0; $i < $result->num_rows; $i++) {//пока счетчик меньше кол-во полей выбран тз бд
            $row[] = $result->fetch_assoc();

        }
        return $row;
    }

    public function get_tovar($id)
    {//вернет массив данных по товару
        $result = $this->ins_driver->select(
            array('title', 'text', 'img', 'keywords', 'description'),
            'tovar',
            array('tovar_id' => $id, 'publish' => 1)
        );
        return $result[0];
    }

  /*  public function get_pricelist()
    {//методв вернет массив данных для вывода в прайс-листе
//так как метод будет формировать сложный sql запрос, то бе
        $sql = "SELECT brands.brand_id,brands.brand_name,brands.parent_id,
        tovar.title,tovar.anons,tovar.price FROM brands
        LEFT JOIN tovar
        ON tovar.brand_id=brands.brand_id
        WHERE brands.brand_id
        IN(SELECT brands.parent_id FROM tovar
        LEFT JOIN brands
        ON tovar.brand_id=brands.brand_id
        WHERE tovar.publish='1')
        OR brands.brand_id IN(
         SELECT brands.brand_id
        FROM tovar LEFT JOIN brands
        ON tovar.brand_id=brands.brand_id
        WHERE tovar.publish='1')
        AND tovar.publish='1'";

        $result = $this->ins_driver->ins_db->query($sql);
        //$this->ins_driver  свойство хранит в себе об-кт класса model_driver
        //в классе  model_driver у нас есть свойство ins_db, которое хранит в себе
        //об-кт класса mysqli

        if (!$result) {
            throw new DbException("Ошибка базы данных" . $this->ins_driver->ins_db->errno . "|" .
                $this->ins_driver->ins_db->error);//генерируем исключение

        }

        //проверим кол-во ворвращаемых рядов
        if ($result->num_rows == 0) {//$result - это объект
            return FALSE;
        }
        $myrow = array();
        for ($i = 0; $i < $result->num_rows; $i++) {//пока счетчик меньше кол-во полей выбран тз бд
            $row = $result->fetch_assoc();

            //является ли то, что записано в $row родит категорией или это дочерняя категория
            //мы хотим сделать родит категрии ключами, поэтому нужно отсортировать родит и дочерние категории
            if ($row['parent_id'] === '0') {
                if (!empty($row['title'])) {
                    //разбираемся с родительскими категориями
                    $myrow[$row['brand_id']][$row['brand_name']][] = array(
                        'title' => $row['title'],
                        'anons' => $row['anons'],
                        'price' => $row['price']
                    );
                } else {
                    //если товаров нет
                    $myrow[$row['brand_id']][$row['brand_name']] = array();

                }
            } else {
//разбираемся с дочерними категориями
                $myrow[$row['parent_id']]['sub'][$row['brand_name']][] = array(
                    'title' => $row['title'],
                    'anons' => $row['anons'],
                    'price' => $row['price']
                );
            }

        }


        //переформируем массив

        return $myrow;
    }*/

  /*  public function add_page($title, $text, $position, $keywords, $description)
    {
        //метод добавляет страницу из админки в модель
        $result = $this->ins_driver->insert(
            'pages',
            array('title', 'text', 'position', 'keywords', 'description'),
            array($title, $text, $position, $keywords, $description)

        );
        return $result;
    }

    public function get_page_admin($id)
    {
        $result = $this->ins_driver->select(//$this->ins_driver//сво-во хрант об-т класса Model_Driver
            array('page_id', 'title', 'keywords', 'description', 'text', 'position'),
            'pages',
            array('page_id' => $id)
        );
        return $result[0];
    }

    public function edit_page($id, $title, $text, $position, $keywords, $description)
    {
        $result = $this->ins_driver->update(//$this->ins_driver//сво-во хрант об-т класса Model_Driver
            'pages',
            array('page_id', 'title', 'text', 'position', 'keywords', 'description'),
            array($id, $title, $text, $position, $keywords, $description),
            array('page_id' => $id)//WHERE page_id = $id
        );
        return $result;
    }

    public function delete_page($id)
    {
        $result = $this->ins_driver->delete(
            'pages',
            array('page_id' => $id)
        );
        return $result;
    }

    public function add_news($title, $text, $anons, $keywords, $description)
    {
        $result = $this->ins_driver->insert(//обращаемся к объекту класса Model_Driver
            'news',
            array('title', 'text', 'anons', 'date', 'keywords', 'description'),
            array($title, $text, $anons, time(), $keywords, $description)
        );
        return $result;
    }


    public function get_admin_news_text($id)
    {
        $result = $this->ins_driver->select(//$this->ins_driver - обращаемся к об-у класса
            array('news_id', 'title', 'anons', 'text', 'date', 'keywords', 'description'),
            'news',
            array('news_id' => $id)
        );
        return $result[0];
    }


    public function edit_news($title, $text, $anons, $id, $keywords, $description)
    {
        $result = $this->ins_driver->update(//$this->ins_driver - обращаемся к об-у класса
            'news',
            array('title', 'text', 'anons', 'date', 'keywords', 'description'),
            array($title, $text, $anons, time(), $keywords, $description),
            array('news_id' => $id)
        );
        return $result;
    }

    public function delete_news($id)
    {
        $result = $this->ins_driver->delete(//$this->ins_driver - обращаемся к об-у класса
            'news',
            array('news_id' => $id)
        );
        return $result;
    }*/

    public function get_parent_brands()
    {
        $result = $this->ins_driver->select(//$this->ins_driver - обращаемся к об-у класса
            array('brand_id', 'brand_name'),
            'brands',
            array('parent_id' => 0)
        );
        return $result;
    }

    public function add_category($title, $parent)
    {//добавление категории
        $result = $this->ins_driver->insert(//обращаемся к объекту класса Model_Driver
            'brands',
            array('brand_name', 'parent_id'),
            array($title, $parent)
        );
        return $result;
    }

/*    public function add_new_type($type_name)
    {//название типа (товара), который ввел пользователь
        //данный мтеод должен вставить тип в бд и вернуть id данного типа
        $result = $this->ins_driver->insert(//обращаемся к объекту класса Model_Driver
            'type',//название таблицы, в которую мы вставляем данные
            array('type_name'),
            array($type_name),
            TRUE//вернет id вставленной записи
        );
        return $result;
    }*/

    public function add_goods($id, $title, $anons, $text,
                              $img,

                              $publish,
                              $price,
                              $keywords,
                              $description)
    {
        $result = $this->ins_driver->insert(
            'tovar',
            array('title', 'anons', 'text', 'img', 'brand_id',  'publish', 'price', 'keywords', 'description'),
            array($title, $anons, $text, $img, $id, $publish, $price, $keywords, $description)
        );
        return $result;

    }

    public function get_tovar_adm($id)//метод вернет массив данных по товару, id котрого указан
    {
        $result = $this->ins_driver->select(
            array('tovar_id', 'title', 'text', 'img', 'keywords', 'description', 'anons',
                'brand_id', 'publish', 'price'),
            'tovar',
            array('tovar_id' => $id)
        );
        return $result[0];
    }


    public function edit_goods($id, $title, $anons, $text, $img, $publish, $price, $category, $keywords, $description)
    {
        if ($img) {
            $result = $this->ins_driver->update(
                'tovar',
                array('title', 'anons', 'text', 'img', 'publish', 'price', 'brand_id', 'keywords', 'description'),
                array($title, $anons, $text, $img, $publish, $price, $category, $keywords, $description),
                array('tovar_id' => $id)
            );

        } else {
            $result = $this->ins_driver->update(
                'tovar',
                array('title', 'anons', 'text', 'publish', 'price', 'brand_id', 'keywords', 'description'),
                array($title, $anons, $text, $publish, $price, $category, $keywords, $description),
                array('tovar_id' => $id)
            );
        }
        return $result;
    }

    public function delete_tovar($id)
    {
        $result = $this->ins_driver->delete(//$this->ins_driver - обращаемся к об-у класса
            'tovar',
            array('tovar_id' => $id)
        );
        return $result;
    }


    public function get_category($id)
    {
        $result = $this->ins_driver->select(
            array('brand_id', 'brand_name', 'parent_id'),
            'brands',
            array('brand_id' => $id)
        );
        return $result[0];
    }

    public function edit_category($title, $parent, $id)
    {
        //для parent == 0
            $result = $this->ins_driver->update(
                'brands',
                array('brand_name', 'parent_id'),
                array($title, $parent),
                array('brand_id' => $id)
            );
        return $result;
    }

    public function delete_category($id)
    {
        $result = $this->ins_driver->delete(//$this->ins_driver - обращаемся к об-у класса
            'brands',
            array('brand_id' => $id)
        );
        $result2 = $this->ins_driver->update(//$this->ins_driver - обращаемся к об-у класса
            'tovar',
            array('brand_id'),
            array(0),
            array('brand_id' => $id)
        );
        if ($result) {
            if ($result2) {
                return TRUE;
            } else {
                return $result2;
            }
        } else {
            return $result;
        }
    }//т.е.после того как удалили категорию установим товарам это йкатегорий brand_id = 0

    //теперь нам нужно изменит ьзначение поля Brand_id на 0 у всех товаров, у которых была удалена категория
   /* public function get_type_adm($id)
    {//массив данных по выброному типу
        $result = $this->ins_driver->select(
            array('type_id', 'type_name', 'in_header'),
            'type',
            array('type_id' => $id)
        );
        return $result[0];
    }
    public function edit_types($type_name,$in_header,$id){//изменение типа товара
        $result = $this->ins_driver->update(
            'type',
            array('type_name', 'in_header'),
            array($type_name,$in_header),
            array('type_id' => $id)
        );
        return $result;
    }
    public function delete_types($id){//удаление типа товара
        $result = $this->ins_driver->delete(//$this->ins_driver - обращаемся к об-у класса
            'type',
            array('type_id' => $id)
        );
        return $result;
    }*/
   /* //////////////метод обновит 2 записи - для главной страницы и стра-ы показа контактной информации
    public function update_page_options($option,$new_id,$old = FALSE){//тип, который мы должны установить
//id стр-ы, кот выбрал пользователь
        // id прошлой страницы, если не назначена гл стр-а
        if(!$old){
        $sql = "UPDATE pages SET type='$option' WHERE page_id = '$new_id'";
        }else{
           //обновим две запаси в бд с помощью одного sql запроса
            $sql = "UPDATE pages SET type = CASE
                    WHEN page_id = '$new_id' THEN '$option'



                    WHEN page_id = '$old' THEN 'post' END
                    WHERE page_id IN('".$new_id."','".$old."')";
        }

        //echo $sql;
        //exit();
        $result = $this->ins_driver->ins_db->query($sql);

        if(!$result) {
            throw new DbException("Ошибка базы данных: ".$this->ins_db->errno." | ".$this->ins_db->error);
            return FALSE;
        }

        return TRUE;
    }*/

}































