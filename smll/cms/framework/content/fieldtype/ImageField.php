<?php
namespace smll\cms\framework\content\fieldtype;

use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;

use smll\framework\io\file\interfaces\IFileReference;

use smll\framework\utils\Guid;

use smll\framework\io\file\interfaces\IFileUploadManager;

use smll\cms\framework\content\fieldtype\interfaces\IFileFieldType;
use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;
/**
 * 
 * @author ksdkrol
 * [DefaultRenderer(smll\cms\framework\ui\fields\ImageFieldRenderer)]
 */
class ImageField implements IFileFieldType {
	
	private $name;
	private $dataType = "linkGuid";
	private $enabled = true;
	/**
	 * 
	 * @var IFieldSettings
	 */
	private $settings = null;
	
	private $manager = null;
	
	private $maxFileSize = '8MB';
	
	private $renderer = null;
	
	public function setRenderer(IFieldRenderer $renderer) {
		$his->renderer = $renderer;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function renderField($data, $parameters = null) {
		
		if($this->settings->getMaxInputValues() > 1 || $this->settings->getMaxInputValues() == 0 ) {
			$output = '';
			
			if(is_array($data)) {
				foreach($data as $d) {
					$output .= '
							<input name="'.$this->name.'[]" type="file" value="'.$d.'" id="input-'.$this->name.'"/>';
				}
			} else {
				for($i = 0; $i < 2; $i++) {
					$output .= '
							<input name="'.$this->name.'[]" type="file" value="" id="input-'.$this->name.'"/>';
				}
			}
		
			
			return $output;
		}
		
		$output = '';
		
		if($data instanceof IFileReference) {
			$output .= '<img src="/gamescom/'.$data->getFilename().'" /><input type="hidden" name="'.$this->name.'" value="'.$data->getIdent().'" /><a href="#" class="btn"><i class="icon-remove-sign"></i></a>';
		} else {
		
			$output .= '<input name="'.$this->name.'" type="file" value="'.$data.'" id="input-'.$this->name.'"/>';
		
		}
		return $output;
	}
	public function validateField($data, $parameters = null) {
		
		/**
		 * @todo Make sure that this validates the file(s) correctly.
		 */
		return true;
	}
	
	public function getPropertyDataType() {
		return $this->dataType;
	}
	public function setPropertyDataType($datatype) {}

	public function renderFieldJson($data) {}
	
	public function setData($data) {}
	
	public function processData($data) {
		$file = null;
		if($data != null) {
			$file = $this->manager->processFile($this->name);
		}
		return $file;
	}
	public function getErrorMessage() {}
	
	public function setFieldSettings(IFieldSettings $settings) {
		$this->settings = $settings;
	}
	
	public function setFileUploadManager(IFileUploadManager $manager) {
		$this->manager = $manager;
	}
}