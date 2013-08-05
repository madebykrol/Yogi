<?php
namespace smll\framework\helpers;
use smll\framework\utils\Boolean;

use smll\framework\io\Request;
use smll\framework\utils\AnnotationHandler;
use smll\framework\IApplication;
use smll\framework\utils\HashMap;

use \Exception;
use \ReflectionProperty;
use \ReflectionClass;



class Html {

    public static $currentForm = 0;
    public static $formStack = array();
    public static $builder = null;
    public static $uiState = Html::UI_STATE_VIEW;


    public static function renderAction($action, $controller = null, HashMap $extras = null) {
        global $application;
        if($controller == null) {
            $controller = get_class($application->getCurrentExecutingController());
        } else {
            $controller .= "Controller";
        }

        $request = new Request(null, array('q' => $controller."/".$action), null);
        $result = $application->processAction($controller, $action, $extras);
        
        return $result;
    }

    public static function propertyFor($object, $property) {

        $rModel = new ReflectionClass(get_class($object));
        $output = '';
        if($rModel->hasProperty($property)) {
            $property= $rModel->getProperty($property);
            $annotationHandler = new AnnotationHandler();

            if($annotationHandler->hasAnnotation('FormField', $property)) {
    
                $name = $property->getName();
                $structuredAnnotations = self::getFormFieldAnnotations($property);
    
                if($property->isPublic() && $property->getValue($object) != null) {
                    $defaultValue = $property->getValue($object);
                } else {
                    $defaultValue = $structuredAnnotations->get('DefaultValue');;
                }
    
                $type = $structuredAnnotations->get('InputType');
    
                $output .= self::validationMessageFor($name);
    
                if($type == 'text') {
                    $output .= self::textfieldFor($name, $defaultValue, $structuredAnnotations->get('Placeholder'));
                } else if($type == 'password') {
                    $output .= self::passwordFor($name, $defaultValue, $structuredAnnotations->get('Placeholder'));
                } else if($type == 'textarea') {
                    $output .= self::textareaFor($name, $defaultValue, $structuredAnnotations->get('Placeholder'));
                } else if($type == 'boolean' || $type == 'checkbox') {
                    $output .= self::checkboxFor($name, Boolean::parseValue($defaultValue));
                } else if($type == 'radiobutton') {
                    if($defaultValue instanceof HashMap || is_array($defaultValue)) {
                        if(is_array($defaultValue)) {
                             
                        } else {
                            foreach($defaultValue->getIterator() as $partName => $value) {
                                $nameLabel = explode("|", $partName);
                                $output .= self::checkboxFor($name."[".$nameLabel[0]."]", $value);
                                if(isset($nameLabel[1])) {
                                    $output .= self::label($nameLabel[1], $name."[".$nameLabel[0]."]");
                                }
                            }
                        }
                    }
                     
                } else if($type == 'hidden') {
                    $output .= self::hiddenFor($name, $defaultValue);
                }
            }
        }
        return $output;
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

    public static function actionLink($text, $action, $controller = null, 
            HashMap $queryString = null, HashMap $extras = null)
    {
        global $application;
        if ($controller == null) {
            $controller = $application->getCurrentExecutingController();
        }
        if (strtolower($action) == "index") {
            $action = "";
        } else {
            $action = "/".$action;
        }
        $controller = "/".$controller;
        $params = "";
        if (isset($queryString) && $queryString->getLength() > 0) {
            $params .= "?";
            $i = 0;
            foreach ($queryString->getIterator() as $var => $qString) {
                $params.=$var."=".$qString;
                $i++;
                if($queryString->getLength() > $i) {
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


                $annotations = self::getFormFieldAnnotations($property);
                
                return self::label($annotations->get('Label'), $name);
            }
        }
    }
    
    public static function beginForm($action = null, $controller = null)
    {
    
        global $application;
    
        self::$uiState = Html::UI_STATE_EDIT;
        if (!$application instanceof IApplication) {
            throw new Exception();
        }
        
        if ($controller == null) {
            $controller = $application->getCurrentExecutingController();
        }
        
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
        
        $output = "<form method=\"POST\" action=\"".$postBack.$params."\" enctype=\"multipart/form-data\">";
    
        return $output;
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

        $output = "<form method=\"POST\" action=\"".$postBack.$params."\" enctype=\"multipart/form-data\">";

        $annotationHandler = new AnnotationHandler();
        $rClass = new \ReflectionClass(get_class($object));
        foreach($rClass->getProperties() as $property) {
            $output .= self::labelFor($object, $property->getName());
            $output .= self::propertyFor($object, $property->getName());
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
    const UI_STATE_VIEW = 0;
    const UI_STATE_EDIT = 1;
    const UI_STATE_PREVIEW = 2;
}