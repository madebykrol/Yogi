<?php
interface IAnnotationHandler {
	public function getAnnotations($m);
	public function hasAnnotation($annotation, $m);
	public function parseAnnotation($annotation);
}