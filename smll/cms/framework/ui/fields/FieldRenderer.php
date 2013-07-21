<?php
namespace smll\cms\framework\ui\fields;

use smll\framework\utils\HashMap;
use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;

abstract class FieldRenderer implements IFieldRenderer
{

    private $data;
    private $error;
    private $name;
    private $options;

    public function setValidationError($error)
    {
        $this->error = $error;
    }

    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setFieldName($name)
    {
        $this->name = $name;
    }

    public function getData()
    {
        return $this->data;
    }
    
    public function getFieldName()
    {
        return $this->name;
    }

    public function setOptions(HashMap $options)
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
