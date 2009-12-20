<?php
class CrudController extends Lagged_Crud_Controller
{
    public function init()
    {
        $this->model = new Model_Crud;
        parent::init();
    }
}