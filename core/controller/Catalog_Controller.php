<?php
class Catalog_Controller extends Base{

    protected $type = FALSE;//тип товара по назначению(type) или бренд(brand)
    protected $id = FALSE;//идентификаторы категорий либо иден типов по назначению
    protected $parent = FALSE;
    protected $navigation;
    protected $catalog;
    protected $krohi;//сво-во хранит массив хлебных крошек

  protected function input($param = array())//параметры для навигации и не только пост данные
  {
      parent::input();
      $this->home_page = TRUE;
      $this->title .= "Каталог";
      $this->need_right_side = FALSE;//скрываем right_bar.php


      if(isset($param['brand'])){//код для принятия параметра brand/2
        $this->type = "brand";
        $this->id = $this->clear_int($param['brand']);
      }elseif(isset($param['type'])){
          $this->type = "type";
          $this->id = $this->clear_int($param['type']);
      }elseif(isset($param['parent'])){
          $this->parent = TRUE;
          $this->id = $this->clear_int($param['parent']);
      }

//принятие номера страницы для оторажения
        if(isset($param['page'])){
        $page = $this->clear_int($param['page']);
            if($page == 0){
                $page = 1;
            }
      }else{
            $page = 1;
        }


      if($this->type){

          if(!$this->id){
            return;
          }


       $pager = new Pager(//параметры для конструктора класса Pager
                $page,
                'tovar',
                 array($this->type.'_id'=>$this->id, 'publish'=>1),//поле для фильтрации - type_id либо brand_id
                 'tovar_id',
                 'ASC',
                  QUANTITY,
                  QUANTITY_LINKS
       );

          $this->krohi = $this->ob_m->get_krohi($this->type,$this->id);//метод вернет массив хлебных крошек
          //print_r($this->krohi);

          $this->keywords = $this->krohi[0][$this->type.'_name'].','.$this->krohi[1]['brand_name'];
          $this->description = $this->krohi[0][$this->type.'_name'].','.$this->krohi[1]['brand_name'];




      }  //в параметре $this->type хранится "type", во втором параметре идентификатор категории


      elseif($this->parent){
          if(!$this->id){
              return;
          }
         $ids = $this->ob_m->get_child($this->id);//метод get_child() вернет все

          if(!$ids){
              return;
          }

          //id дочерних категорий, которые входят в конкретную родит. категорию
          $pager = new Pager(//параметры для конструктора класса Pager
              $page,//номер страницы для отображения
              'tovar',
              array('brand_id'=>$ids, 'publish'=>1),//поле для фильтрации - type_id либо brand_id
              'tovar_id',
              'ASC',
              QUANTITY,
              QUANTITY_LINKS,
              array("IN","=")
          );

          $this->type = "parent";
         $this->krohi = $this->ob_m->get_krohi('brand',$this->id);//вывод хлебных крошек для "все типы"

          $this->keywords = $this->krohi[0]['brand_name'];
          $this->description = $this->krohi[0]['brand_name'];

      }
      elseif(!$this->type && !$this->parent){//если в строке запроса нет ни параметра type, ни parent
//то значит пользователь хочет получит ькаталог
          $pager = new Pager(//параметры для конструктора класса Pager
              $page,
              'tovar',
              array('publish'=>1),//поле для фильтрации - type_id либо brand_id
              'tovar_id',
              'ASC',
              QUANTITY,
              QUANTITY_LINKS
          );
          $this->krohi[0]['brand_name'] = "Каталог" ;
          $this->keywords = "Промэнерго, каталог товаров";
          $this->description = "Промэнерго, каталог товаров";
      }

      //вызовем два метода, чтобы получить массив данных для вывода товаров на экран
      // и массив ссылок для постраничной навигации
      if(is_object($pager)){
          $this->navigation = $pager->get_navigation();
          $this->catalog = $pager->get_posts();
          //var_dump($this->catalog);
      }



  }
    protected function output()
    {
        $previous = FALSE;
        if($this->type && $this->id){
            $previous = "/".$this->type."/".$this->id;// - "/type/1"

        }
        $this->content = $this->render(VIEW.'catalog_page', array(
                     'catalog'=>$this->catalog,
                    'navigation'=>$this->navigation,
                        'previous'=>$previous,// дополнит параметр для формирования ссылок
                                             // - "/type/1"
                       'krohi'=>$this->krohi
        ));
        $this->page = parent::output();
        return $this->page;//полная страницы сайта

    }
}
















