<?php
namespace smll\cms\framework\helpers;

use smll\framework\io\Request;

use \smll\cms\framework\content\interfaces\IContent;

use smll\cms\framework\content\fieldtype\interfaces\ITaxonomyFieldType;

use smll\framework\utils\HashMap;

use smll\framework\io\file\FileReference;

use smll\framework\utils\Guid;

use smll\cms\framework\content\fieldtype\interfaces\IFileFieldType;

use smll\cms\framework\ui\FieldTypeFactory;

use smll\framework\io\db\DB;

use smll\framework\utils\interfaces\IAnnotationHandler;

use smll\cms\framework\content\interfaces\IPageData;
use smll\cms\framework\content\interfaces\IBlockData;
use smll\framework\IApplication;
use smll\framework\helpers\Html;
use smll\framework\utils\AnnotationHandler;
use \Exception;
use \ReflectionClass;
use \ReflectionProperty;


class CmsHtml
{
    
    public static $uiState = Html::UI_STATE_VIEW;
    
    
    private function __construct()
    {
        
    }
    
    
    public static function beginForm($action, $controller)
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
    
    public static function closeForm()
    {
        self::$uiState = Html::UI_STATE_VIEW;
        $output = '</form>';
    }
    
    public static function cmsFormForPage(IContent $page)
    {
        global $application;
        
        self::$uiState = Html::UI_STATE_EDIT;
        if (!$application instanceof IApplication) {
            throw new Exception();
        }
        $pageDataRepository = $application->getContainer()->get(
                'smll\cms\framework\content\utils\interfaces\IPageDataRepository');

        
        $settings = $application->getContainer()->get(
                'smll\framework\settings\interfaces\ISettingsRepository');

        $connectionStrings = $settings->get('connectionStrings');
        $defaultConnectionString = $connectionStrings['Default']['connectionString'];

        $postBack = "";

        $reflectionPage = new ReflectionClass(get_class($page));

        $currentController = $application->getCurrentExecutingController();

        /**
         * @todo build in the damn pageDataRepository
        */

        $output = "<form method=\"POST\" action=\"".$postBack."\" enctype=\"multipart/form-data\">";

        $db = new DB($defaultConnectionString);
        $type = $db->query('SELECT id FROM content_type WHERE name = ?', $reflectionPage->getShortName());

        $fields = $db->query('SELECT * FROM content_definition WHERE fkContentTypeId = ? ORDER BY weightOrder DESC', $type[0]->id);
        
        $tabs = array();

        foreach ($fields as $i => $field) {
             
            $tab = 'Content';
            if (isset($field->tab)) {
                $tab = $field->tab;
            }
            
            if(!isset($tabs[$tab])) {
                $tabs[$tab] = array();
            }
            
            $tabs[$tab][] = '<label>'.$field->displayName.'</label>'.self::propertyFor($page, $field->name);
        }


        $output .= '<div class="tabbable" style="margin-bottom: 18px;">
                <ul class="nav nav-tabs">
                ';
        $i = 1;
        foreach ($tabs as $title => $tab) {
            $class = "";
            if ($i == 1) {
                $class = "active";
            }
            $output .='<li class="'.$class.'"><a href="#tab'.$i.'" data-toggle="tab">'.$title.'</a></li>';
            $i++;
        }
        $output .='</ul>
                <div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">';
        $i = 1;
        foreach ($tabs as $title => $tab) {
             
            $class = "";
            if($i == 1) {
                $class = "active";
            }
             
            $output .='<div class="'.$class.' tab-pane" id="tab'.$i.'">';
            foreach ($tab as $field) {
                $output .= $field;
            }
            $output .='</div>';
            $i++;
        }
        $output .= '</div></div>';
        $output .= '<input type="hidden" name="page" value="'.$reflectionPage->getShortName().'" />';
        
        
        self::$uiState = Html::UI_STATE_VIEW;
        return $output;
    }

    public static function cmsFormForBlock(IBlockData $block)
    {
        global $application;
    }
    
    /**
     * 
     * @param object $model
     * @param string $field
     */
    public static function propertyFor($model, $field)
    {
        $output = "";
        global $application;
        
        if (!$application instanceof IApplication) {
            throw new Exception();
        }
        
        if($model instanceof IContent) {
            
            $reflectionClass = new ReflectionClass(get_class($model));
            
            switch (self::$uiState) {
                case Html::UI_STATE_EDIT :
                    
                    
                    // Begin handelling fieldtype annotations
                    
                    $pageDataRepository = $application->getContainer()->get(
                            'smll\cms\framework\content\utils\interfaces\IPageDataRepository');
                    
                    $annotationHandler = $application->getContainer()->get(
                            'smll\framework\utils\interfaces\IAnnotationHandler');
                    
                    $connectionStrings;
                    
                    $settings = $application->getContainer()->get(
                            'smll\framework\settings\interfaces\ISettingsRepository');
                    
                    $connectionStrings = $settings->get('connectionStrings');
                    $defaultConnectionString = $connectionStrings['Default']['connectionString'];
                    
                    if (!$annotationHandler instanceof IAnnotationHandler) {
                        throw new Exception();
                    }
                    
                    // Get field type
                    $fieldFactory = $application
                        ->getContainer()
                        ->get('smll\cms\framework\ui\interfaces\IFieldTypeFactory');
                    
                    $postBack = "";
                    
                    $defaultFieldSettings = $annotationHandler->getAnnotation("ContentField",$reflectionClass->getProperty($field));
                    $hashMap = new HashMap($defaultFieldSettings[1]);
                    
                    $db = new DB($defaultConnectionString);
                    
                    $result = $db->query('SELECT * FROM field_definition_type WHERE name = ?',$hashMap->get('Type'));
                    $defType = $result[0];
                    
                    $rendererResult = $db->query('SELECT renderer FROM content_definition_renderer  AS c_d_r
                            JOIN content_type AS c_t ON (c_t.id = c_d_r.fkContentTypeId)
                            JOIN content_definition AS c_d ON (c_d.id = c_d_r.fkContentDefinitionId)
                            WHERE c_t.name = ? AND c_d.name = ?', $reflectionClass->getShortName(), $field);
                    
                    if(is_array($rendererResult) && count($rendererResult) > 0) {
                        $hashMap->add('renderer', $rendererResult[0]->renderer);
                    }
                    
                    foreach ((array)$field as $prop => $val) {
                        $hashMap->add($prop, $val);
                    }
                    
                    $rField = $fieldFactory->buildFieldType($defType->assembler, $hashMap);
                     
                    //print_r($rField);
                     
                    $rField->setName($field);
                     
                    $value = $reflectionClass->getProperty($field)->getValue($model);
                    if ($rField instanceof IFileFieldType) {
                        if (is_array($value)) {
                            foreach ($value as $index => $val) {
                                if (($guid = Guid::parse($val)) != null) {
                                    // get FileReference
        
                                    $db->where(array('ident', '=', $guid));
                                    $ref = $db->get('file_reference');
                                    $db->flushResult();
                                    $db->clearCache();
        
                                    $reference = new FileReference();
                                    $reference->setIdent($guid);
                                    $reference->setId($ref[0]->id);
                                    $reference->setFilename($ref[0]->filename);
                                    $reference->setFilesize($ref[0]->size);
                                    $reference->setMime($ref[0]->mime);
        
                                    $value[$index] = $reference;
                                }
                            }
                        } else {
                            if (($guid = Guid::parse($value)) != null) {
                                $db->where(array('ident', '=', $guid));
                                $ref = $db->get('file_reference');
                                $db->flushResult();
                                $db->clearCache();
        
                                $reference = new FileReference();
                                $reference->setIdent($guid);
                                $reference->setId($ref[0]->id);
                                $reference->setFilename($ref[0]->filename);
                                $reference->setFilesize($ref[0]->size);
                                $reference->setMime($ref[0]->mime);
        
                                $value = $reference;
                            }
                        }
                    }
                    
                    $rField->setUIState(self::$uiState);
                    
                    $output = $rField->renderField($value);
                    
                    break;
                
                case Html::UI_STATE_VIEW : 
                    $output = $reflectionClass->getProperty($field)->getValue($model);
                    break;
            }
        } else if(is_array($model)) {
            $output = $model[$field];
        }
        
        return $output;
    }
    
}