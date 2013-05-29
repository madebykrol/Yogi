<?php
interface IApplication {
	public function run();
	public function getCurrentExecutingController();
	
}