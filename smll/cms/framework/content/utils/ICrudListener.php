<?php
namespace smll\cms\framework\content\utils\interfaces;
interface ICrudListener {
	public function onPageCreate();
	public function onPageRetrieve();
	public function onPageUpdate();
	public function onPageDelete();
	
	public function onPageTypeCreate();
	public function onPageTypeRetrieve();
	public function onPageTypeUpdate();
	public function onPageTypeDelete();
	
}