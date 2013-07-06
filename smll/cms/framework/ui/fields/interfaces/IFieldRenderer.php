<?php
namespace smll\cms\framework\ui\fields\interfaces;

interface IFieldRenderer {
	public function render();
	public function setValidationError($error);
	public function setData($data);
	public function setFieldName($name);
}