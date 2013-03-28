<?php
interface IRouter {
	public function lookup(Request $path);
}