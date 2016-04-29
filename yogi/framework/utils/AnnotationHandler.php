<?php
namespace yogi\framework\utils;
use yogi\framework\utils\interfaces\IAnnotationHandler;
use \ReflectionClass;
use \ReflectionMethod;
use \ReflectionProperty;
class AnnotationHandler implements IAnnotationHandler {
	
	public function __construct() {
		
	}
	
	public function getAnnotations($m) {
		if($m instanceof ReflectionMethod || $m instanceof ReflectionProperty || $m instanceof ReflectionClass) {
			$comment = $m->getDocComment();
			
			$commentLines = explode("\n", $comment);
			$matches = array();
			foreach($commentLines as $commentLine) {
				
				$regexp = new Regexp('^.+?\[(.+?)\]$');
				$regexp->setOption("m");
				$match = $regexp->find(trim($commentLine));
				if(isset($match[1][0])) {
					
					$matches[] = $match[1][0];
				}
			}
			
			return $matches;
		}
		return null;
	}
	
	public function hasAnnotation($annotation, $m) {
		if($m instanceof ReflectionMethod || $m instanceof ReflectionProperty || $m instanceof ReflectionClass) {
			
			$comment = $m->getDocComment();
			
			$regexp = new Regexp('\['.$annotation.'\]|\['.$annotation.'\(.+?\)]');
			$matches = $regexp->find($comment);
			if($matches[0] != null) {
				return true;
			}
			
			return false;
		}
	}
	
	public function getAnnotation($annotation, $m) {
		if($m instanceof ReflectionMethod || $m instanceof ReflectionProperty  || $m instanceof ReflectionClass) {
				
			$comment = $m->getDocComment();
			$regexp = new Regexp('\[('.$annotation.')\]|\[('.$annotation.'\(.+?\))\]');
			$matches = $regexp->find($comment);
			
			if($matches[0] != null) {
				if($matches[2] != null && $matches[2][0] != null) {
					return $this->parseAnnotation($matches[2][0]);
				} else {
					return $this->parseAnnotation($matches[1][0]);
				}
			}
		}
	}
	
	public function parseAnnotation($annotation) {
		$innerDeclarations = array();
		
		$regexp = new Regexp('(.+?)[=|\(|\]]');
		$find = $regexp->find($annotation);
		
		$tAnnotation = array();
		
		if(isset($find[1]) && count($find[1]) > 0) {
			$regexp = new Regexp('\((.+?)\)$');
			
			$innerDeclarations = $regexp->find($annotation);
			
			if(isset($innerDeclarations[1]) && count($innerDeclarations[1]) > 0) {
					
				$innerDeclaration = $innerDeclarations[1][0];
				
				
				$annotation = explode("(", $annotation);
				
				$annotation = $annotation[0];
				
				$innerDeclaration = explode(",", $innerDeclaration);
				
				$tmp = array();
				foreach($innerDeclaration as $pos => $declaration) {
					$declaration = explode("=", $declaration, 2);
					if(count($declaration) == 1) {
						$tmp[$pos] = $declaration[0];
					} else {
						$tmp[trim($declaration[0])] = $declaration[1];
					}
					
				} 
				
				if(count($tmp) > 0) {
					$innerDeclaration = $tmp;
				}
				
				$tAnnotation = array($annotation, $innerDeclaration);
				
				
					
			} else {
				
				$tAnnotation = explode("=", $annotation);
				
			}
		} else {
			$tAnnotation = array($annotation, true);
		}
		
		return $tAnnotation;
	}
}