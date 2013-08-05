<?php
namespace smll\cms\framework\content\interfaces;

use smll\cms\framework\content\interfaces\IContent;

interface IPageData extends IContent
{
    public function getPageReference();
}