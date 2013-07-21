<?php
namespace smll\cms\framework\content\fieldtype\interfaces;

use smll\framework\utils\HashMap;

interface IFieldType
{
    public function setName($string);
    public function renderField($data, $parameters = null);
    public function validateField($data, $parameters = null);

    public function getPropertyDataType();
    public function setPropertyDataType($datatype);

    public function renderFieldJson($data);

    public function setData($data);

    public function setFieldSettings(HashMap $settings);
    public function getFieldSettings();

    public function processData($data, $index = 0);

    public function getErrorMessage();
    public function isMultifield($boolean = null);
}