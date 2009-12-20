<?php
set_include_path(dirname(__FILE__) . '/../library:' . get_include_path());

require_once 'Lagged/Crud/Form.php';
class MyTable extends Lagged_Crud_Form
{
    protected $_primary = 'id';
    protected $_schema  = 'foo';
    protected $_name    = 'bar';
}

// $mytable = new MyTable;
// echo $mytable->getForm(); // create
// $record = 1;
// echo $mytable->getForm($record); // update

require_once 'Lagged/Crud/Controller.php';
class BarController extends Lagged_Crud_Controller
{
    public function init()
    {
        $this->model = new MyTable;
        $this->model->setControllerName(get_class($this));
        parent::init();
    }
}