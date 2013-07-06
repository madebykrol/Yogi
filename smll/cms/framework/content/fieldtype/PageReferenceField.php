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
class PageReferenceField implements IFieldType {
	
	private $name;
	private $dataType = "pageRef";
	private $enabled = true;
	private $error = "";
	private $multifield = false;
	private $renderer = null;
	
	public function __construct() {
		
	}
	
	public function setName($name) {
		$this->name = $name;
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
	public function validateField($data, $parameters = null) {
		return true;
	}
	
	public function getPropertyDataType() {
		return $this->dataType;
	}
	public function processData($data) {
		return $data;
	}
	
	public function getErrorMessage() {}
	
	public function setPropertyDataType($datatype) {
		$this->dataType = $datatype;
	}
	
	public function setRenderer(IFieldRenderer $renderer) {
		$this->renderer = $renderer;
	} 
	
	public function renderFieldJson($data) {}

	public function setData($data) {}
	
	public function setFieldSettings(IFieldSettings $settings) {}
	
}