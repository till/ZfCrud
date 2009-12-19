<?php
class Lagged_Crud_Controller extends Zend_Controller_Action
{
    protected $model;

    protected $primaryKey;

    public function init()
    {
        // when you extend, you need to put your model here
        // $this->model = new Lagged_Crud_Form;

        $this->primaryKey = $this->model->getPrimaryKey();
    }

    public function createAction()
    {
        
    }

    public function deleteAction()
    {
    }

    public function readAction()
    {
        $id = $this->_request->getParam('id', null);

        if ($id === null) {
            $records = $this->model->fetchAll();
            $this->view->assign('records', $records->toArray());
        } else {
            $record = $this->model->fetchRow("{$this->primaryKey} = ?", $id);
            $this->view->assign('record', $record->toArray());
        }
    }

    public function updateAction()
    {
        $id = $this->_request->getParam('id');
        if ($this->_request->isPost() !== true) {
            $form = $this->model->getForm($id);
            $this->view->assign('form', $form);
            return;
        }

        $form = $this->model->getForm();
        $form->populate($this->_request->getPost());
        if (!$form->isValid()) {
            $this->view->assign('form', $form);
            return;
        }

        $this->model->update(
            $this->_request->getPost(),
            "{$this->primarykey} = ?", $id
        );
        // redirect to read
    }
}