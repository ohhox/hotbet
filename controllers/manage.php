<?php

class Manage extends Controller {

    function __construct() {
        parent::__construct();

        $this->view->formatPage = 'manage';

        $this->view->currentPage = "manage";
        $this->view->elem('body')->addClass('hidden-tobar settings-page');
    }

    public function navTrigger() {
        if( isset($_REQUEST['status']) ){
            Session::init();                          
            Session::set('isPushedLeft', $_REQUEST['status']);
        }
        else{
            $this->error();
        }
    }

    public function index() {
        header('Location:' . URL .'manage/users/admin');
    }

    public function member($section="index"){

        if( !in_array($section, array('index', 'points', 'level')) ){
            $this->error();
        }

        // get data point
        if( $section=='points' ){
            $this->view->post = $this->model->query('member')->getPoint();
        }


        if( $section=='level' ){

            // print_r($this->model->query('member')->listsLevel()); die;
            $this->view->results = $this->model->query('member')->levelLists();
        }

        $this->view->section = "member/{$section}";
        $this->view->render("manage/display");
    }

    public function users($section="index") {

        if( !in_array($section, array('index', 'admin', 'operator')) ){
            $this->error();
        }

        if( $section=='admin' ){
            $access_id = 1;
            $this->view->access_id = $access_id;
            $this->view->results = $this->model->query('admin')->lists( array('access_id'=>$access_id) );
        }

        if($section=='operator'){
            $access_id = 3;
            $this->view->access_id = $access_id;
            $this->view->results = $this->model->query('admin')->lists( array('access_id'=>$access_id) );
        }

        $this->view->section = "users/{$section}";
        $this->view->render("manage/display");
    }

}