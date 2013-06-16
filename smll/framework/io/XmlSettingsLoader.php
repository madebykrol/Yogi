<?php
class XmlSettingsLoader implements ISettingsLoader {
	
	private $file = null;
	
	public function __construct($file) {
		$this->file = $file;
	}
	
	public function getSettings() {
		$dom = new DOMDocument(null, null);
		$dom->load($this->file);
		$settings = array(
			
		);
		$appSettings = $dom->getElementsByTagName('appSettings')->item(0);
		if($appSettings->hasChildNodes()) {
			$nodes = $dom->getElementsByTagName('appSettings')->item(0)->childNodes;
			foreach($nodes as $node) {
				if(!($node->nodeType instanceof DOMText) && $node->nodeName != "#text") {
					$settings[$node->nodeName] = array();
					if($node->hasChildNodes()) {
						$this->traverseNodeChildren($node, $settings[$node->nodeName]);
					}
				}
			}
		}
		
		return $settings;
	}
	
	private function traverseNodeChildren($node, &$settings) {
		$nodes = $node->childNodes;
		foreach($nodes as $node) {
			$name = $node->nodeName;
			if(!($node->nodeType instanceof DOMText) && $node->nodeName != "#text") {
				if($node->nodeName == "add") {
					
					$attributes = array();
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
				if($node->hasChildNodes()) {
					$this->traverseNodeChildren($node, $settings[$node->nodeName]);
				}
			}
		}
	}
}