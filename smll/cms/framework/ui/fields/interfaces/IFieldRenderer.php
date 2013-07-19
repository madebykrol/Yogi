<?php
namespace smll\cms\framework\ui\fields\interfaces;

use smll\framework\utils\HashMap;

interface IFieldRenderer {
	public function render();
	public function setValidationError($error);
	public function setData($data);
	public function setFieldName($name);
	public function setOptions(HashMap $options);
}