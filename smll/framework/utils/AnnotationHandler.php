<?php
class AnnotationHandler implements IAnnotationHandler {
	
	public function __construct() {
		
	}
	
	public function getAnnotations($m) {
		if($m instanceof ReflectionMethod || $m instanceof ReflectionProperty) {
			$comment = $m->getDocComment();
			$regexp = new Regexp('\[(.+?)\]');
			$matches = $regexp->find($comment);
			return $matches[1];
		}
		return null;
	}
	
	public function hasAnnotation($annotation, $m) {
		if($m instanceof ReflectionMethod || $m instanceof ReflectionProperty) {
			
			$comment = $m->getDocComment();
			$regexp = new Regexp('\['.$annotation.'\]');
			$matches = $regexp->find($comment);
			
			if($matches[0] != null) {
				return true;
			}
			
			return false;
		}
	}
	
}