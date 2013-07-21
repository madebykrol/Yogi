<?php
namespace smll\cms\framework\content\fieldtype;

use smll\framework\utils\HashMap;

use smll\cms\framework\content\fieldtype\interfaces\IFieldSettings;

class FieldSettings implements IFieldSettings
{

    private $enabled;
    private $required;
    private $maxInputValues = 1;
    private $longStringSetting = 1024;
    /**
     * @var HashMap
     */
    private $settings;

    public function __construct()
    {
        $this->settings = new HashMap();
    }

    public function getMaxInputValues()
    {
        return $this->maxInputValues;
    }
    public function setMaxInputValues($max)
    {
        $this->maxInputValues = $max;
    }

    public function isEnabled($boolean = null)
    {
    }
    public function isRequired($boolean = null)
    {
    }

    public function getLongStringSettings()
    {
    }
    public function setLongStringSettings($length)
    {
    }

    public function add($setting, $value)
    {
        $this->settings->add($setting, $value);
    }

    public function get($setting)
    {
        return $this->settings->get($setting);
    }
}