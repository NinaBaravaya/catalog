<?php
abstract class Base_Error extends Base_Controller {

    protected $message_err;
    protected $title;

    protected function input() {
        $this->title = 'Страница показа ошибок';
    }

    protected function output() {

        $page = $this->render(VIEW.'error_page',array(
                'title' => $this->title,
                'error' => $this->message_err
            )
        );
        return $page;
    }
}
?>