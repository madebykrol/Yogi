<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

/**
 *
 * @author ksdkrol
 * [DefaultRenderer(smll\cms\framework\ui\fields\DateRenderer)]
 */
class DateTimeField extends BaseFieldType
{

    protected $dataType = "string";

    public function renderField($data, $parameters = null)
    {
        return '<input name="'.$this->name.'" type="text" class="dateField" value="'.$data.'"/>';
    }

}