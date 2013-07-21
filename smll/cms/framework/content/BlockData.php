<?php
namespace smll\cms\framework\content;

use smll\cms\framework\content\interfaces\IBlockData;


class BlockData implements IBlockData
{
    /**
     * [Editable]
     * [ContentField(Type=Text, DisplayName=Subject, Required=true, Tab=Block, Searchable=true, WeightOrder=-50)]
     * @var unknown
     */
    public $subject;
    /**
     * [Editable]
     * [ContentField(Type=Text, DisplayName=Content, Required=true, Tab=Block, Searchable=true, WeightOrder=-50)]
     * @var unknown
     */
    public $content;
}