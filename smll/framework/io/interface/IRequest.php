<?php
interface IRequest {
	public function getPath();
	public function getAccept();
	public function getQueryString($var);
	public function getPostData();
	public function getGetData();
	public function getApplicationRoot();
}