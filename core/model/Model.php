<?php
defined('PROM') or exit('Access denied');
class Model
{
    //класс по шаблону проектирования Singleton
    static $instance;

    public $ins_driver;//свойство будет хранить объект класса Model_Driver

    static function get_instance()
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        } else {
            return self::$instance = new self;
        }
    }

    private function __construct()
    {
        try {
            $this->ins_driver = Model_Driver::get_instance();

        } catch (DbException $e) {
            exit();
        }


    }

    public function get_catalog_brands()
    {//метод вернет производителей товаров
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
                //дописываем в родит ячейку дочернюю категорию с ключом next_lvl
            }
        }
        return $arr;
    }

    public function get_child($id)
    {//метод вернет все товары дочерних категорий
        $result = $this->ins_driver->select(
            array('brand_id'),
            'brands',
            array('parent_id' => $id)//условие фильтрации
        );

        if ($result) {
            $row = array();
            foreach ($result as $item) {
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
            //во втором подзапросе вытаскиваем родит категорию у данной дочерней категории
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
    }//т.е.после того как удалили категорию установим товарам этой категорий brand_id = 0

}































