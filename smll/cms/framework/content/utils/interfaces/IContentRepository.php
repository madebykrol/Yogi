<?php
namespace smll\cms\framework\content\utils\interfaces;

use smll\cms\framework\content\interfaces\IContentProperty;

use smll\cms\framework\content\interfaces\IPropertyCriteriaCollection;

use smll\framework\utils\Guid;

use smll\cms\framework\content\interfaces\IContent;

interface IContentRepository
{
    public function addContent(IContent $content);
    public function getContent(Guid $content);
    public function removePropertyForContent($contentId, IContentProperty $prop);
    public function setPropertyForContent($contentId, IContentProperty $prop);
    public function findContentWithCriteria(IPropertyCriteriaCollection $criteriaCollection);
}