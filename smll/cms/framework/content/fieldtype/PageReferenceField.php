<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;

use smll\cms\framework\content\fieldtype\interfaces\IFieldType;

/**
 * 
 * 
 * @author Kristoffer "mbk" Olsson
 * @version beta 1.0
 * 
 * PageReferenceField is a field processing and rendering a PageReference input
 * 
 * 
 * [DefaultRenderer(smll\cms\framework\ui\fields\PageReferenceRenderer)]
 */
class PageReferenceField extends BaseFieldType {

	protected $dataType = "pageRef";
	
	public function __construct() {
		
	}
	
	public function renderField($data, $parameters = null) {
		/**
		 * @todo Rewrite prototype code.
		 */
		$output = "";
		if($this->multifield) {
			foreach($data as $index => $d) {
				$this->renderer->setData($d);
				$this->renderer->setValidationError($this->error);
				$this->renderer->setFieldName($this->name."[]");
				
				$output .= $this->renderer->render();
			}
		} else {
			$this->renderer->setData($data);
			$this->renderer->setValidationError($this->error);
			$this->renderer->setValidationError($this->error);
			$this->renderer->setFieldName($this->name);
			$output = $this->renderer->render();
		}
		
		return $output;
	}
	
}