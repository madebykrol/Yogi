<?php
namespace smll\cms\framework\content;

use smll\cms\framework\content\interfaces\IPageProperty;

class PageProperty implements IPageProperty
{

    private $value = null;
    private $pageDefId = null;
    private $index = null;
    private $ignoreIfNull = false;
    private $dataType = "string";

    public function setValue($value)
    {
        $this->value = $value;
    }
    public function setPageDefinitionId($id)
    {
        $this->pageDefId = $id;
    }
    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function getValue()
    {
        return $this->value;
    }
    public function getPageDefinitionId()
    {
        return $this->pageDefId;
    }
    public function getIndex()
    {
        return $this->index;
    }

    public function ignoreIfNull($boolean = null)
    {
        if (is_bool($boolean)) {
            $this->ignoreIfNull = $boolean;
        }
        return $this->ignoreIfNull;
    }

    public function setDataType($datatype)
    {
        $this->dataType = $datatype;
    }
    public function getDataType()
    {
        return $this->dataType;
    }

}