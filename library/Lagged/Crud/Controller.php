<?php
abstract class Lagged_Crud_Controller extends Zend_Controller_Action
{
    /**
     * @var Zend_Db_Table_Abstract
     * @see self::init()
     */
    protected $model;

    /**
     * @var mixed
     */
    protected $primaryKey;

    /**
     * @var Zend_Controller_Action_Helper_Redirector
     */
    protected $redirector;

    /**
     * init()
     *
     * @return void
     */
    public function init()
    {
        // when you extend, you need to put your model here
        // $this->model = new Lagged_Crud_Form;
        // parent::init()

        /**
         * register Zend_Controller_Action_Helper_Redirector
         */
        $this->redirector = $this->_helper->getHelper('Redirector');

        $this->primaryKey = $this->model->getPrimaryKey();
    }

    public function createAction()
    {
        if ($this->_request->isPost() !== true) {
            $form = $this->model->getForm();
            $this->view->assign('form', $form);
            return;
        }

        $form = $this->model->getForm();
        $form->populate($this->_request->getPost());
        if (!$form->isValid()) {
            $this->view->assign('form', $form);
            return;
        }

        $id = $this->model->insert(
            $this->_request->getPost(),
        );

        $this->redirector->gotoSimple(
            'read',
            $this->model->getControllerName(),
            null,
            array('id' => $id,)
        );
    }

    public function deleteAction()
    {
    }

    public function indexAction()
    {
        $this->redirector->gotoSimple(
            'read',
            $this->model->getControllerName()
        );
    }

    public function readAction()
    {
        $id = $this->getId();

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
        $id = $this->getId();
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

        $this->redirector->gotoSimple(
            'read',
            $this->model->getControllerName(),
            null,
            array('id' => $id,)
        );
    }

    /**
     * @return mixed
     */
    protected function getId()
    {
        return $this->_request->getParam('id', null);
    }
}