<?php
interface IRoute {
	public function getName();
	
	public function getUrl();
	
	public function getDefaults();
}