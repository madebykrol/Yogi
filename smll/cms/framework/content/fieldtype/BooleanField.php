<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

/**
 * 
 * @author ksdkrol
 * [DefaultRenderer(smll\cms\framework\ui\fields\BooleanRenderer)]
 */
class BooleanField extends BaseFieldType {

	protected $dataType = "boolean";
	
	public function renderField($data, $parameters = null) {
		
		$checked = "";
		if($data == 1) {
			 $checked = 'Checked="checked"';
		}
		return '<input name="'.$this->name.'" '.$checked.' type="checkbox" value="1"/>';
	}
	
}