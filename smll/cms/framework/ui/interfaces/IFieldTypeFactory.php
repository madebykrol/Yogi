<?php
namespace smll\cms\framework\ui\interfaces;

use smll\framework\utils\HashMap;

interface IFieldTypeFactory
{
    public function buildFieldType($type);
    public function buildFieldSettings(HashMap $settings);
    public function attachFieldInjector(IFieldInjector $injecter);
}