<?php
/**
 *
 * - Fill out a form
 *    - POST to PHP
 *  - Sanitize
 *  - Validate
 *  - Return Data
 *  - Write to Database
 */
require 'Form/Val.php';
require_once 'Form/Create_Form.php';
require_once 'Form/Success_Form.php';

class Form
{

    /** @var array $_currentItem The immediately posted item*/
    private $_currentItem = null;
    
    /** @var array $_postData Stores the Posted Data */
    private $_postData = array();
    
    /** @var object $_val The validator object */
    private $_val = array();
    
    /** @var array $_error Holds the current forms errors */
    private $_error = array();
    
    /**
     * __construct - Instantiates the validator class
     * 
     */
    public function __construct() 
    {
        $this->_val = new Val();
        $this->_success = new Success_Form();
        $this->_create = new Create_Form();
    }

    public function create( $form=null, $dataPost=null ){

        switch ($form) {
            case 'login':
                
                return $this->_success->form( $form, $dataPost );
                break;
            
            default:
                return $this->_create->_config();
                break;
        }
    }
    
    /**
     * post - This is to run $_POST
     * 
     * @param string $field - The HTML fieldname to post
     */
    public function post($field)
    {
        $this->_postData[$field] = $_POST[$field];
        $this->_currentItem = $field;
        
        return $this;
    }
    
    /**
     * fetch - Return the posted data
     * 
     * @param mixed $fieldName
     * 
     * @return mixed String or array
     */
    public function fetch($fieldName = false)
    {
        if ($fieldName) 
        {
            if (isset($this->_postData[$fieldName]))
            return $this->_postData[$fieldName];
            
            else
            return false;
        } 
        else 
        {
            return $this->_postData;
        }
        
    }
    
    /**
     * val - This is to validate
     * 
     * @param string $typeOfValidator A method from the Form/Val class
     * @param string $arg A property to validate against
     */
    public function val($typeOfValidator, $arg = null)
    {
        if ($arg == null)
        $error = $this->_val->{$typeOfValidator}($this->_postData[$this->_currentItem]);
        else
        $error = $this->_val->{$typeOfValidator}($this->_postData[$this->_currentItem], $arg);
        
        if ($error)
        $this->_error[$this->_currentItem] = $error;
        
        return $this;
    }
    
    /**
     * submit - Handles the form, and throws an exception upon error.
     * 
     * @return boolean
     * 
     * @throws Exception 
     */
    public function submit() {
        if (empty($this->_error)) 
        {
            return true;
        } 
        else 
        {
            $str = '';
            foreach ($this->_error as $key => $value)
            {
                $str .= $key . '=>' . $value . ",";
            }
            throw new Exception( $str );
        }
    }

    public function verify($typeOfValidator, $text, $arg=null){
        return $this->_val->{$typeOfValidator}($text, $arg);
    }

    public function check($arr, $key, $val) {   

        $err = "";
        foreach ($arr as $i => $k) {
            if( $k==$key ){
                $err = $this->verify($key, $val);
                break;
            }
        }

        return $err;
    }
}