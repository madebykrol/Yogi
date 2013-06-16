<?php
class Html {
	
	public static $currentForm = 0;
	public static $formStack = array();
	
	public static function renderAction($action, $controller = null, HashMap $extras = null) {
		global $application;
		if($controller == null) {
			$controller = get_class($application->getCurrentExecutingController());
		} else {
			$controller .= "Controller";
		}
		
		$request = new Request(null, array('q' => $controller."/".$action), null);
		$request->init();
		return $application->processAction($controller, $action, $extras);
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
		
		if(!$application instanceof IApplication) {
			throw new Exception();
		}
		
		self::$currentForm++;
		self::$formStack[self::$currentForm] = array();
		
		$postBack = "";
		if($controller != "") {
			$postBack.=$controller."/";
		}
		if($action != "") {
			$postBack.=$action."/";
		}
		
		$currentController = $application->getCurrentExecutingController();
		
		$output = "<form method=\"POST\" action=\"".$postBack."\">";
		
		$annotationHandler = new AnnotationHandler();
		$rClass = new ReflectionClass(get_class($object));
		foreach($rClass->getProperties() as $property) {
			
			
			if($annotationHandler->hasAnnotation('FormField', $property)) {
				
				$name = $property->getName();
				$structuredAnnotations = self::getFormFieldAnnotations($property);
				
				if($property->isPublic() && $property->getValue($object) != null) {
					$defaultValue = $property->getValue($object);
				} else {
					$defaultValue = $structuredAnnotations->get('DefaultValue');;
				}
				
				if($structuredAnnotations->get('Label')) {
					$output .= self::label($structuredAnnotations->get('Label'), $name);
				}
				
				$type = $structuredAnnotations->get('InputType');
				
				$output .= self::validationMessageFor($name);
				
				if($type == 'text') {
					$output .= self::textfield($name, $defaultValue, $structuredAnnotations->get('Placeholder'));
				} else if($type == 'password') {
					$output .= self::password($name, $defaultValue, $structuredAnnotations->get('Placeholder'));
				} else if($type == 'textarea') {
					$output .= self::textarea($name, $defaultValue, $structuredAnnotations->get('Placeholder'));
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
	
	public static function password($name, $value = null, $placeholder = null, $extras = null) {
		return "<input type=\"password\" value=\"".$value."\" name=\"".$name."\" placeholder=\"".$placeholder."\">";
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
			$annotation = $annotationHandler->parseAnnotation($annotation);
			if($annotation[0] != "FormField") {
				$structuredAnnotations->add($annotation[0], $annotation[1]);
			}
		}
		
		return $structuredAnnotations;
	}
	
	public static function validationMessageFor($field) {
		
		global $application;
		if(!$application instanceof IApplication) {
			throw new Exception();
		}
		
		$currentController = $application->getCurrentExecutingController();
		
		$modelState = $currentController->getModelState();
		
		$msg = $modelState->getErrorMessageFor($field);
		
		if($msg != null) {
			return "<div class=\"validate-error\">".$msg."</div>";
		}
		
		return null;
	}
	
}