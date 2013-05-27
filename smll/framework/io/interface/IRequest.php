<?php
interface IRequest {
	public function getPath();
	public function getAccept();
	public function get($var);
}