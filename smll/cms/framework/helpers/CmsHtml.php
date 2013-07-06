<?php
namespace smll\cms\framework\helpers;
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


class CmsHtml {
	public static function cmsFormForPage(IPageData $page) {
		global $application;
		
		if(!$application instanceof IApplication) {
			throw new Exception();
		}
		$contentRepository = $application->getContainer()->get(
				'smll\cms\framework\content\utils\interfaces\IContentRepository');
		
		$annotationHandler = $application->getContainer()->get(
				'smll\framework\utils\interfaces\IAnnotationHandler');
		
		$connectionStrings;
		
		$settings = $application->getContainer()->get(
				'smll\framework\settings\interfaces\ISettingsRepository');
		
		$connectionStrings = $settings->get('connectionStrings');
		$defaultConnectionString = $connectionStrings['Default']['connectionString'];
		
		if(!$annotationHandler instanceof IAnnotationHandler) {
			throw new Exception();
		}
		
		// Get field type
		$fieldFactory = $application
			->getContainer()
				->get('smll\cms\framework\ui\interfaces\IFieldTypeFactory');
		
		$postBack = "";
		
		$reflectionPage = new ReflectionClass(get_class($page));
				
		$currentController = $application->getCurrentExecutingController();
		
		
		
		/**
		 * @todo build in the damn contentRepository
		 */
		
		$output = "<form method=\"POST\" action=\"".$postBack."\" enctype=\"multipart/form-data\">";
                
		$db = new DB($defaultConnectionString);
		$type = $db->query('SELECT id FROM page_type WHERE name = ?', $reflectionPage->getShortName());
		
		$fields = $db->query('SELECT * FROM page_definition WHERE fkPageTypeId = ? ORDER BY weightOrder DESC', $type[0]->id);
		
		$tabs = array('Content' => array(), 'Settings' => array(), 'Menu' => array());
		
		foreach($fields as $i => $field) {
			
			$tab = 'Content';
			if(isset($field->tab)) {
				$tab = $field->tab;
			}
			
			
			$typeId = $field->fkPageDefinitionTypeId;
			$result = $db->query('SELECT * FROM page_definition_type WHERE id = ?',$typeId);
			
			$defType = $result[0];
			
			
			$rField = $fieldFactory->buildFieldType($defType->assembler);
			$rField->setName($field->name);
			
			$value = $reflectionPage->getProperty($field->name)->getValue($page);
			if($rField instanceof IFileFieldType) {
				if(($guid = Guid::parse($value)) != null) {
					// get FileReference
					
					$db->where(array('ident', '=', $value));
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
			
			$tabs[$tab][] = '<label>'.$field->displayName.'</label>'.$rField->renderField($value);
		} 

		
		$output .= '<div class="tabbable" style="margin-bottom: 18px;">
              <ul class="nav nav-tabs">
							';
		$i = 1;
		foreach($tabs as $title => $tab) {
			$class = "";
			if($i == 1) {
				$class = "active";
			}
    	$output .='<li class="'.$class.'"><a href="#tab'.$i.'" data-toggle="tab">'.$title.'</a></li>';
    	$i++;
		}
    $output .='</ul>
    		<div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">';
    $i = 1;
    foreach($tabs as $title => $tab) {
    	
    	$class = "";
    	if($i == 1) {
    		$class = "active";
    	}
    	
    	$output .='<div class="'.$class.' tab-pane" id="tab'.$i.'">';
    		foreach($tab as $field) {
    			$output .= $field;
    		}
    	$output .='</div>';
    	$i++;
    }
		$output .= '</div></div>';
		$output .= '<input type="hidden" name="page" value="'.$reflectionPage->getShortName().'" />';
		return $output;
	}
	
	public static function cmsFormForBlock(IBlockData $block) {
		global $application;
	}
}