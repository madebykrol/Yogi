<?php
namespace yogi\framework\io;
use yogi\framework\settings\interfaces\ISettingsLoader;
use yogi\framework\utils\Boolean;

/**
 * XmlSettingsLoader implements ISettingsLoader and loads settings from 
 * a XML file.
 * 
 * The settings structure generated meeds to be revisited...
 * 
 * @author Kristoffer "mbk" Olsson
 *
 */



class XmlSettingsLoader implements ISettingsLoader {
	
	private $file = null;
	
	public function __construct($file) {
		$this->file = $file;
	}
	
	public function getSettings() {
		$dom = new \DOMDocument(null, null);
		$dom->load($this->file);
		
		// Settings placeholder
		$settings = array(
			
		);
		
		// Get document root element
		$appSettings = $dom->getElementsByTagName('appSettings')->item(0);
		
		// Get all second level nodes. These are generally the settings we need.
		if($appSettings->hasChildNodes()) {
			$nodes = $dom->getElementsByTagName('appSettings')->item(0)->childNodes;
			foreach($nodes as $node) {
				// we don't bother with empty elements, whitespaces or text elements.
				if(!($node->nodeType instanceof \DOMText) && $node->nodeName != "#text") {
					$settings[$node->nodeName] = array();
					if($node->hasChildNodes()) {
						// This is supposed to be recursive.
						$this->traverseNodeChildren($node, $settings[$node->nodeName]);
					}
				}
			}
		}
		
		return $settings;
	}
	
	/**
	 * Recursive settings lookup.
	 * @param unknown $node
	 * @param unknown $settings
	 */
	private function traverseNodeChildren($node, &$settings) {
		$nodes = $node->childNodes;
		$name = $node->nodeName;
		
		// Get xml attributes, and add them to the setting
		if($node->hasAttributes()) {
			foreach($node->attributes as $attr => $value) {
				if($attr == "name") {
					$name = $value->nodeValue;
				} else {
					$attributes[$attr] = $value->value;
				}
			}
			
			$settings[$name] = array();
			$settings[$name] = $attributes;
		}
		
		foreach($nodes as $node) {
			$name = $node->nodeName;
			if(!($node->nodeType instanceof DOMText) && $node->nodeName != "#text") {
					
				$attributes = array();
				foreach($node->attributes as $attr => $value) {
					if($attr == "name" && $node->nodeName == "add") {
						$name = $value->nodeValue;
					} else {
						if(Boolean::isBoolean($value->value)) {
							$attributes[$attr] = Boolean::parseValue($value->value);
						} else {
							$attributes[$attr] = $value->value;
						}
					}
				}
				$settings[$name] = array();
				$settings[$name] = $attributes;
					
				// Keep traversing, just kee-eep traversing
				if($node->hasChildNodes()) {
					$this->traverseNodeChildren($node, $settings[$node->nodeName]);
				}
			}
		}
	}
}