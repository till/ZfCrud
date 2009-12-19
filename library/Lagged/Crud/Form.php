<?php
class Lagged_Crud_Form extends Zend_Db_Table_Abstract
{
    protected $baseName = 'crud_';

    /**
     * @var Zend_Form_Decorator $decorator
     */
    protected $decorator;

    /**
     * @var Zend_Form $form
     */
    protected $form;

    /**
     * @var string $formAction
     */
    protected $formAction = '/crud';

    /**
     * @param mixed $var
     *
     * @return $this
     */
    public function accept($var)
    {
        if ($var instanceof Zend_Form) {
            $this->form = $var;
            return $this;
        }
        if ($var instanceof Zend_Form_Decorator) {
            $this->decorator = $var;
            return $this;
        }
        throw UnexpectedValueException("Waddap.");
    }

    public function getBaseName()
    {
        return $this->baseName;
    }

    /**
     * Create a form, automatically from a table.
     *
     * @return Zend_Form
     */
    public function getForm($record = null)
    {
        $this->form = new Zend_Form;
        $this->form->setAttrib('id', $this->baseName . $this->_name);
        $this->form->setMethod('post');

        $this->addFields();

        if ($record !== null) {
            $this->populate($record);
            $this->form->setAction($this->formAction . '/update');
        } else {
            $this->form->setAction($this->formAction . '/create');
        }

        return $this->form;
    }

    public function setBaseName($baseName)
    {
        $this->baseName = $baseName;
        return $this;
    }

    public function setControllerName($controllerName)
    {
        throw new Exception("Not implemented.");
        // parse Module_FooController

        $action = '/';

        if (strstr($controllerName, '_')) {
            list($module, $controller) = explode('_', $controllerName);
            $action .= $module . '/';
        } else {
            $controller = $controllerName;
        }
        $controller = str_replace('Controller', '', $controller);

        $action .= $controller;

        $this->formAction = $action;

        return $this;
    }

    /**
     * @return void
     */
    protected function addFields()
    {
        foreach ($this->_cols as $col) {

            // ignore keys
            if (in_array($col, $this->_primary)) {
                continue;
            }

            $element = new Zend_Form_Element_Text(
                $this->baseName . $col,
                array('label' => ucwords(str_replace('_', ' ', $col)),)
            );

            if ($this->decorator !== null) {
                $element->addDecorator($this->decorator);
            }
            $this->form->addElement($element);
        }
        $this->form->addElement('submit', 'save', array('label' => 'Save to be safe!'));
    }

    /**
     * Populate generated form with data.
     *
     * @param mixed $record
     *
     * @return void
     */
    protected function populate($record)
    {
        if ($record === null) {
            throw new LogicException("Cannot fill, if no record is supplied.");
        }

        $select = $this->select();

        foreach ($this->_primary as $key) {
            $select->where("$key = ?", $this->getAdapter()->quote($record));
        }

        $row  = $this->fetchRow($select);
        $data = $row->toArray();
        $this->form->populate($data);
    }
}
