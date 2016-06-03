<?php
namespace yogi\framework\helpers;
use yogi\framework\utils\Boolean;

use yogi\framework\io\Request;
use yogi\framework\utils\AnnotationHandler;
use yogi\framework\IApplication;
use yogi\framework\utils\HashMap;

use \Exception;
use \ReflectionProperty;
use \ReflectionClass;



class Html {
	
	public static $currentForm = 0;
	public static $formStack = array();
	public static $builder = null;
	public static $uiState = 'view';
	
	
	public static function renderAction($action, $controller = null, HashMap $extras = null) {
		global $application;
		if($controller == null) {
			$controller = get_class($application->getCurrentExecutingController());
		} else {
			$controller .= "Controller";
		}
		
		$request = new Request(array(), array('q' => $controller."/".$action), null);
		return $application->processAction($controller, $action, $extras, $request);
	}
	
	public static function propertyFor($model, $property) {
		
		$rModel = new ReflectionClass(get_class($model));
		
		if($rModel->hasProperty($property)) {
			$rProp = $rModel->getProperty($property);
			$annotationHandler = new AnnotationHandler();
			
			if($annotationHandler->hasAnnotation('FormField', $rProp)) {
				
				$annotations = self::getFormFieldAnnotations($rProp);
				
			}
		}
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
	
	public static function actionUrl($action, $controller = null, HashMap $extras = null) {
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
		
		$params = "";
		if(isset($extras) && $extras->getLength() > 0) {
			$params .= "?";
			$i = 0;
			foreach($extras->getIterator() as $var => $extra) {
				$params.=$var."=".$extra;
				$i++;
				if($extras->getLength() > $i) {
					$params.="&";
				}
			}
		}
		
		$request = new Request();
		
		return $request->getApplicationRoot().$controller.$action.$params;
	}
	
	public static function actionLink($text, $action, $controller = null, HashMap $extras = null) {
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
		$params = "";
		if(isset($extras) && $extras->getLength() > 0) {
			$params .= "?";
			$i = 0;
			foreach($extras->getIterator() as $var => $extra) {
				$params.=$var."=".$extra;
				$i++;
				if($extras->getLength() > $i) {
					$params.="&";
				}
			}
		}
		
		$request = new Request();
		
		return "<a href=\"".$request->getApplicationRoot().$controller.$action.$params."\">".$text."</a>";
		
	}
	
	public static function url($uri) {
		$request = new Request();
		
		return $request->getApplicationRoot()."/".$uri;
	}
	
	
	public static function beginForm($action = "", $controller = "", HashMap $extras = null) {
		global $application;
		self::$uiState = Html::UI_STATE_EDIT;
		self::$currentForm++;
		self::$formStack[self::$currentForm] = array();
		
		$output = "<form method=\"POST\" action=\"\">";
			
		return $output;
	}
	
	public function textBoxFor($object, $name) {
		$annotationHandler = new AnnotationHandler();
		$rClass = new \ReflectionClass(get_class($object));
		
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
		$rClass = new \ReflectionClass(get_class($object));
		
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
	
	public static function beginFormFor($object, $action = "", $controller = "", HashMap $extras = null) {
		global $application;
		
		self::$uiState = Html::UI_STATE_EDIT;
		
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
		if($postBack != "") {
			$request = new Request();
			$postBack = $request->getApplicationRoot()."/".$postBack;
		}
		
		$params = "";
		if(isset($extras) && $extras->getLength() > 0) {
			$params .= "?";
			$i = 0;
			foreach($extras->getIterator() as $var => $extra) {
				$params.=$var."=".$extra;
				$i++;
				if($extras->getLength() > $i) {
					$params.="&";
				}
			}
		}
		
		$currentController = $application->getCurrentExecutingController();
		
		$output = "<form method=\"POST\" action=\"".$postBack.$params."\">";
		
		$annotationHandler = new AnnotationHandler();
		try {
			$rClass = new \ReflectionClass(get_class($object));
			foreach($rClass->getProperties() as $property) {
					
					
				if($annotationHandler->hasAnnotation('FormField', $property)) {
					$structuredAnnotations = self::getFormFieldAnnotations($property);
					
					$name = $property->getName();
					$type = $structuredAnnotations->get('InputType');
					
					if($property->isPublic() && $property->getValue($object) != null) {
						$defaultValue = $property->getValue($object);
					} else {
						$defaultValue = $structuredAnnotations->get('DefaultValue');;
					}
					
					$customTag = $structuredAnnotations->get('CustomTag') ?: "div";
					$customClass = $structuredAnnotations->get('CustomClass') ?: $type."-controller";
					$customId = $structuredAnnotations->get('CustomId') ?: "form".self::$currentForm."-".$name;
					$customTemplate = $structuredAnnotations->get('CustomTemplate') ?: "{label}{error}{field}{required}";
					
					$output .= "<".$customTag." id=\"".$customId."\" class=\"".$customClass."\">\n";
					$output .= $customTemplate;
					if($structuredAnnotations->get('Label')) {
						$output = str_replace("{label}", self::label($structuredAnnotations->get('Label'), $name), $output);
					}
					
					if($structuredAnnotations->get('Required')) {
					$output = str_replace("{required}", 
							(!self::fieldIsValid($name)) 
							? "<span class=\"required-error\">*</span>" 
							: "<span class=\"required\">*</span>", 
							$output);
					} else {
						$output = str_replace("{required}", "", $output);
					}
					
					$output = str_replace("{error}", self::validationMessageFor($name), $output);
			
					if($type == 'text') {
						$output = str_replace("{field}", 
								self::textfieldFor($name, $defaultValue, $structuredAnnotations->get('Placeholder')), $output);
					} else if($type == 'password') {
						$output = str_replace("{field}", 
								self::passwordFor($name, $defaultValue, $structuredAnnotations->get('Placeholder')), $output);
					} else if($type == 'textarea') {
						$output = str_replace("{field}", 
								self::textareaFor($name, $defaultValue, $structuredAnnotations->get('Placeholder')), $output);
					} else if($type == 'boolean' || $type == 'checkbox') {
						$output = str_replace("{field}", 
								self::checkboxFor($name, Boolean::parseValue($defaultValue)), $output);
					} else if($type == 'radiobutton') {
						if($defaultValue instanceof HashMap || is_array($defaultValue)) {
							if(is_array($defaultValue)) {
								$defaultValue = new HashMap($defaultValue);
							}
							
							$field = "";
							foreach($defaultValue->getIterator() as $partName => $value) {
								$nameLabel = explode("|", $partName);
								
								$field .= self::checkboxFor($name."[".$nameLabel[0]."]", $value);
								if(isset($nameLabel[1])) {
									$field .= self::label($nameLabel[1], $name."[".$nameLabel[0]."]");
								}
							}
							$output = str_replace("{field}", $field, $output);
														
						}
						
					} else if($type == 'hidden') {
						$output = str_replace("{field}", self::hiddenFor($name, $defaultValue), $output);
					}
					$output .= "\n</".$customTag.">";
				}
			}
		} catch(Exception $e) {
			
		}
		
		return $output;
	}
	
	public static function closeForm() {
		self::$uiState = Html::UI_STATE_VIEW;
		return "</form>";	
	}
	
	public static function label($label, $for = null, HashMap $extras = null) {
		return "<label for=\"".$for."\">".$label."</label>";
	}
	
	public static function textfieldFor($name, $value = null, $placeholder = null, HashMap $extras = null) {
		return "<input type=\"text\" value=\"".$value."\" name=\"".$name."\" placeholder=\"".$placeholder."\">";
	}
	
	public static function hiddenFor($name, $value = null) {
		return "<input type=\"hidden\" value=\"".$value."\" name=\"".$name."\">";
	}
	
	public static function radioFor($name, $value = null, HashMap $extras = null) {
		$checked = "";
		if($value) {
			$checked = "CHECKED";
		} 
		
		return "<input name=\"".$name."\" type=\"radio\" $checked value=\"1\"/>";
	}
	
	public static function passwordFor($name, $value = null, $placeholder = null, HashMap $extras = null) {
		return "<input type=\"password\" value=\"".$value."\" name=\"".$name."\" placeholder=\"".$placeholder."\">";
	}
	
	public static function textareaFor($name, $value = null, HashMap $extras = null) {
		return "<textarea name=\"".$name."\">".$value."</textarea>";
	}
	
	public static function checkboxFor($name, $value) {
		
		$checked = "";
		if($value) {
			$checked = "CHECKED";
		} 
		
		return "<input name=\"".$name."\" type=\"checkbox\" $checked value=\"1\"/>";
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
	
	public static function fieldIsValid($field) {

		global $application;
		if(!$application instanceof IApplication) {
			throw new Exception();
		}
		
		$currentController = $application->getCurrentExecutingController();
		
		$modelState = $currentController->getModelState();
		
		return !$modelState->getIsInValid($field);
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
			return "<span class=\"validate-error\">".$msg."</span>";
		}
		
		return null;
	}
	const UI_STATE_VIEW = 'view';
	const UI_STATE_EDIT = 'edit';
	const UI_STATE_PREVIEW = 'preview';
}