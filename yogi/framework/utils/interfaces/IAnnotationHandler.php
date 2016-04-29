<?php
namespace yogi\framework\utils\interfaces;
use yogi\framework\utils\HashMap;

interface IAnnotationHandler {
	/**
	 * @return array;
	 * @param unknown $m
	 */
	public function getAnnotations($m);
	public function hasAnnotation($annotation, $m);
	public function getAnnotation($annotation, $m);
	public function parseAnnotation($annotation);
}