<?php
class Html {
	
	public static $currentForm = 0;
	public static $formStack = array();
	
	public static function renderAction($action, $controller = null, $extras = null) {
		global $application;
		if($controller == null) {
			$controller = $application->getCurrentExecutingController();
		}
		$request = new Request(null, array('q' => $controller."/".$action), null);
		$request->init();
		return $application->run($request);
	}
	
	public static function renderPartial($view, $model) {
		$output = "";
		
		$layout 	= null;
		ob_start();
		include($view);
		$content = ob_get_clean();
		
		if($layout == null) {
			$output = $content;
		} else {
			ob_start();
			include($layout);
			$output = ob_get_clean();
		}
		
		return $output;
	}
	
	public static function actionLink($text, $action, $controller = null, $extras = null) {
		global $application; 
		if($controller == null) {
			$controller = $application->getCurrentExecutingController();
		}
		if(strtolower($action) == "index") {
			$action = "";
		} else {
			$action = "/".$action;
		}
		$controller = "/".$controller;
		
		return "<a href=\"".$application->getApplicationRoot().$controller.$action."\">".$text."</a>";
		
	}
	
	
	public static function beginForm($action = "", $controller = "", $extras = array()) {
		global $application;
		self::$currentForm++;
		self::$formStack[self::$currentForm] = array();
		
		$output = "<form method=\"POST\" action=\"\">";
			
		return $output;
	}
	
	public function textBoxFor($object, $name) {
		$annotationHandler = new AnnotationHandler();
		$rClass = new ReflectionClass(get_class($object));
		
		if($rClass->hasProperty($name)) {
			$property = $rClass->getProperty($name);
			if($annotationHandler->hasAnnotation('FormField', $property)) {
				
				if($property->isPublic()) {
					$defaultValue = $property->getValue($object);
				} else {
					$defaultValue = $structuredAnnotations->get('defaultvalue');;
				}
				
				$annotations = self::getFormFieldAnnotations($property);
				
				return self::textfield($name, $defaultValue, $structuredAnnotations->get('placeholder'));
			}
		}
	}
	
	public function labelFor($object, $name) {
		$annotationHandler = new AnnotationHandler();
		$rClass = new ReflectionClass(get_class($object));
		
		if($rClass->hasProperty($name)) {
			$property = $rClass->getProperty($name);
			if($annotationHandler->hasAnnotation('FormField', $property)) {
		
				if($property->isPublic()) {
					$defaultValue = $property->getValue($object);
				} else {
					$defaultValue = $structuredAnnotations->get('defaultvalue');;
				}
		
				$annotations = self::getFormFieldAnnotations($property);
				
				return self::label($structuredAnnotations->get('label'), $name);
			}
		}
	}
	
	public static function beginFormFor($object, $action = "", $controller = "", $extras = array()) {
		global $application;
		self::$currentForm++;
		self::$formStack[self::$currentForm] = array();
		
		$output = "<form method=\"POST\" action=\"\">";
		
		$annotationHandler = new AnnotationHandler();
		$rClass = new ReflectionClass(get_class($object));
		foreach($rClass->getProperties() as $property) {
			
			
			if($annotationHandler->hasAnnotation('FormField', $property)) {
				
				$name = $property->getName();
				$structuredAnnotations = self::getFormFieldAnnotations($property);
				
				if($property->isPublic()) {
					$defaultValue = $property->getValue($object);
				} else {
					$defaultValue = $structuredAnnotations->get('defaultvalue');;
				}
				
				if($structuredAnnotations->get('label')) {
					$output .= self::label($structuredAnnotations->get('label'), $name);
				}
				
				$type = $structuredAnnotations->get('inputtype');
				
				if($type == 'text') {
					$output .= self::textfield($name, $defaultValue, $structuredAnnotations->get('placeholder'));
				} else if($type == 'textarea') {
					$output .= self::textarea($name, $defaultValue, $structuredAnnotations->get('placeholder'));
				} else if($type == 'boolean') {
					$output .= self::checkbox($name, Boolean::parseValue($defaultValue));
				}
			}
		}
		
		return $output;
	}
	
	public static function closeForm() {
		return "</form>";	
	}
	
	public static function label($label, $for = null, $extras = null) {
		return "<label for=\"".$for."\">".$label."</label>";
	}
	
	public static function textfield($name, $value = null, $placeholder = null, $extras = null) {
		return "<input type=\"text\" value=\"".$value."\" name=\"".$name."\" placeholder=\"".$placeholder."\">";
	}
	
	public static function textarea($name, $value = null, $extras = null) {
		return "<textarea name=\"".$name."\">".$value."</textarea>";
	}
	
	public static function checkbox($name, $checked) {
		
		if($checked) {
			$checked = "CHECKED";
		} else {
			$checked = "";
		}
		
		return "<input type=\"checkbox\" $checked value=\"1\"/>";
	}
	
	public static function getFormFieldAnnotations (ReflectionProperty $property) {	
		
		$annotationHandler = new AnnotationHandler();
		
		$annotations = $annotationHandler->getAnnotations($property);
		$structuredAnnotations = new HashMap();
		foreach($annotations as $annotation) {
			if($annotation != "FormField") {
				$annotation = explode("=", $annotation);
				if(isset($annotation[1])) {
					$structuredAnnotations->add(strtolower($annotation[0]), $annotation[1]);
				} else {
					$structuredAnnotations->add(strtolower($annotation[0]), true);
				}
			}
		}
		
		return $structuredAnnotations;
	}
	
}