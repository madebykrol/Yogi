<?php
namespace smll\cms\framework\interfaces;
use smll\cms\framework\content\interfaces\IContent;

use smll\cms\framework\content\interfaces\IPageData;
interface IContentTypeBuilder {
    public function buildPageType(IContent $pageType, $dataType);
    public function rebuildPageType(IContent $pageType, $dataType);
    public function findPageTypes();
    public function findPageType($type);
}