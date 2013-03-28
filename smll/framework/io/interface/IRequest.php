<?php
interface IRequest {
	public function getPath($index = null);
	public function getAccept();
}