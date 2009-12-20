<?php
class Model_Crud extends Lagged_Crud_Form
{
    protected $_schema  = 'mysql';
    protected $_name    = 'users';
    protected $_primary = 'id';
}