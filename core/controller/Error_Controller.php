<?php
class Error_Controller extends Base_Error {

    protected function input($param = array()) {
        parent::input();

        $er = '';
        $arr = array();
        if(isset($param['mes'])) {
            foreach($param as $key=>$val) {
                $val = $this->clear_str(rawurldecode($val));
                $arr[] = $val;

                $er .= $key.' - '.$val.'|';

            }

            if($_SESSION['error']) {
                foreach($_SESSION['error'] as $k=>$v) {
                    $er .= $k.' - '.$v.'|';
                }
            }
            unset($_SESSION['error']);
            $this->error = $er;
            $this->message_err = $arr;
        }
    }

    protected function output() {
        $this->page = parent::output();

        return $this->page;
    }
}
?>