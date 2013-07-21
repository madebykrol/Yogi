<?php
namespace smll\cms\framework\content;

use smll\cms\framework\content\interfaces\IPropertyCriteria;

class PropertyCriteria implements IPropertyCriteria
{

    private $condition;
    private $name;
    private $type;
    private $required = true;

    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\interfaces\IPropertyCriteria::setCondition()
     */
    public function setCondition(int $condition)
    {
        $this->condition = $condition;
    }

    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\interfaces\IPropertyCriteria::getCondition()
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\interfaces\IPropertyCriteria::setName()
     */
    public function setName($propertyName)
    {
        $this->name = $propertyname;
    }

    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\interfaces\IPropertyCriteria::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\interfaces\IPropertyCriteria::setType()
     */
    public function setType($propertyType)
    {
        $this->type = $propertyType;
    }

    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\interfaces\IPropertyCriteria::getType()
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * (non-PHPdoc)
     * @see \smll\cms\framework\content\interfaces\IPropertyCriteria::isRequired()
     */
    public function isRequired($boolean = null)
    {
        if (is_bool($boolean)) {
            $this->required = $boolean;
        }

        return $this->boolean;
    }

    const CRITERIA_COMPARE_CONDITION_EQUALS                = 1;
    const CRITERIA_COMPARE_CONDITION_UNEQUALS              = 0;

    const CRITERIA_COMPARE_CONDITION_BEGINSWITH            = 2;
    const CRITERIA_COMPARE_CONDITION_ENDSWITH              = 3;
    const CRITERIA_COMPARE_CONDITION_CONTAINS              = 4;

    const CRITERIA_COMPARE_CONDITION_GREATER_THAN          = 5;
    const CRITERIA_COMPARE_CONDITION_LESSER_THAN           = 6;
    const CRITERIA_COMPARE_CONDITION_BETWEEN               = 7;
}