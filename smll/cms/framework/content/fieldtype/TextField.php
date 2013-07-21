<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

class TextField extends BaseFieldType
{
    protected $dataType = "string";

    public function renderField($data, $parameters = null)
    {
        return '<input name="'.$this->name.'" type="text" value="'.$data.'" id="input-'.$this->name.'"/>';
    }

}