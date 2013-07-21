<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

class XmlStringField extends BaseFieldType
{


    protected $dataType = "longString";

    public function renderField($data, $parameters = null)
    {
        return '<textarea name="'
                .$this->name.'" class="xml-field" id="xml-field-'
                        .strtolower($this->name).'"/>'
                                .$data.'</textarea>';
    }

}