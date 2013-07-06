<?php
namespace smll\cms\framework\interfaces;
use smll\cms\framework\content\interfaces\IPageData;
interface IPageTypeBuilder {
	public function buildPageType(IPageData $pageType);
	public function rebuildPageType(IPageData $pageType);
	public function findPageTypes();
	public function findPageType($type);
}