<?php
namespace smll\cms\framework\ui\fields;
use smll\cms\framework\ui\fields\interfaces\IFieldRenderer;

use smll\cms\framework\ui\fields\interfaces;
/**
 * 
 * @author ksdkrol
 *
 */

class PageReferenceRenderer extends FieldRenderer {
	public function render() {
			
		$output = '<div class="input-append">
  							<input name="'.$this->getFieldName().'" type="text" value="'.$this->getData().'" />
  							<a class="btn add-on browser" href="#" data-browser="page"><i class="icon-hdd"></i></a>
							</div>';

		return $output;
	}
}