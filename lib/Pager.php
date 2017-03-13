<?php
class Pager{
    protected $page;//свойство б хранить номер текущей страницы - /page/2

    protected $tablename;//имя таблицы, к которой мы обращаемся, чтобы выбрать данные
    protected $where;//массив, по кот. необ фильтр данные  - поле publish

    protected $order;//сортировка
    protected $napr;
    protected $operand;
    protected $match;//массив для полнотекстого поиска
    protected $post_number;//кол-во записей на одной странице
    protected $number_links;
    protected $db;//объект класса Model_Driver, сво-во записывает конструктроа класса Pager
    protected $total_count;//общее кол-во записей в бд

    public function __construct(
                        $page,
                        $tablename,
                        $where = array(),
                        $order = '',
                        $napr = '',
                        $post_number,
                        $number_link,
                        $operand  = "=",
                        $match = array()
                         )
    {
        $this->page = $page;
        $this->tablename = $tablename;
        $this->where = $where;
        $this->order = $order;
        $this->napr = $napr;
        $this->post_number = $post_number;
        $this->number_link = $number_link;
        $this->operand = $operand;
        $this->match = $match;
        //получить объект класса Model_Driver
        $this->db = Model_Driver::get_instance();//т.к. этот класс singleton
    }

    //метод get_total будет подсчитывать общее кол-во данных в бд
    public function get_total(){

        if(!$this->total_count){//если сво-во пустое, то создадим его
            $result = $this->db->select(
                array("COUNT(*) as count"),
                $this->tablename,
                $this->where,
                $this->order,
                $this->napr,
                FALSE,//параметр Limit не нужен
                $this->operand,
                $this->match
            );//$this->db в данном свойство находится объект класса Model_Driver
            $this->total_count = $result[0]['count'];//общее кол-во записей в бд
        }
        return $this->total_count;//то вернем существ сво-во

    }

    //метод вернет массив данных для вывода на экран
    public function get_posts(){
        $total_post = $this->get_total();//сохр. в премен кол-во данных для вывода на экран

         $number_pages = (int)($total_post/$this->post_number);//кол-во страниц для вывода данных

         if(($total_post % $this->post_number) != 0){
            $number_pages++;
         }

         if($this->page <=0 || $this->page > $number_pages){//если номер страницы меньше нуля или отриц число
           return FALSE;
         }


        //формируем переменную start в зависимости от текущей страницы
        $start = ($this->page - 1)*$this->post_number;//$this->page текущая страница
        //$this->page - 1 так как индекс с нуля в бд
        $result = $this->db->select(
                          array('*'),
                          $this->tablename,
                         $this->where,
            $this->order,
            $this->napr,
            $start.','.$this->post_number,//формируем параметр LIMIT
            $this->operand,
            $this->match
        );//$this->db в данном свойство находится объект класса Model_Driver

        return $result;
    }

//метод будет формировать/возвр. массив навигации
public function get_navigation(){
    $total_post = $this->get_total();//общее кол-во записей для вывода на экран
    //необходимо получить кол-во страниц, которое понадобится для вывода на экран
    $number_pages = (int)($total_post/$this->post_number);//кол-во страниц для вывода данных

    if(($total_post % $this->post_number) != 0){
        $number_pages++;
    }

    if($total_post < $this->post_number || $this->$number_pages){//случай, если общее кол-во записей меньше кол-ва данных,
        // которое необходимо вывести на экран,то нет постран навиг
        return FALSE;


    }
    //формируем массив постраничной навигации (для вывода в шаблоне архива, каталога товаров или поиска по сайту)
    $result = array();
    if($this->page != 1){//если текущая стр не первая
        $result['first'] =1;
        $result['last_page'] = $this->page -1;//стр перед текущей

    }
    if($this->page > $this->number_link + 1 ){//откидываем лишнюю стра-у
        //формируем ссылки слева

        for($i=$this->page - $this->number_link; $i < $this->page; $i++ ){
            $result['previous'][] = $i;
        }

    }else{
        for( $i=1; $i<$this->page; $i++ ){//1<2, для 2 запишем в ячейку единицу
            //для 1 текущей стр условие не выполнится и мы в ячейку ничего не запишем
             //напрмер $this-> page = 2
            $result['previous'][] = $i;//в цикле выводим на экран номера страниц
        }
    }
//выведем тек стр-у
    $result['current'] = $this->page;

    if($this->page + $this->number_link < $number_pages){//проверяем на  тек стр =1
      for($i = $this->page + 1; $i <= $this->page + $this->number_link; $i++){
       $result['next'][] = $i;//убираем ненужные страницы справа от текущей

      }
    }else{
        for($i = $this->page + 1; $i <= $number_pages; $i++){
            $result['next'][] = $i;
        }
    }

    //проверим не явл ли текущая стр последней
    if($this->page != $number_pages){
        $result['next_pages'] = $this->page+1;//">" -формируем ссылку на след страницу
        $result['end'] = $number_pages;//формируем ссылку на повледнюю страницу
    }

    return $result;
}

}


























