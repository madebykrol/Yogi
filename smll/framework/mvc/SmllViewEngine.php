<?php
namespace smll\framework\mvc;
use smll\framework\io\interfaces\IBrowserContext;

use smll\framework\mvc\interfaces\IViewEngine;
use smll\framework\mvc\interfaces\IViewResult;
use smll\framework\utils\ArrayList;

class SmllViewEngine implements IViewEngine {

    private $partialViews = null;
    private $browserContext = null;
    /**
     *
     * @param string $paths
     */
    public function __construct($paths = null, IBrowserContext $browserContext = null) {
        if(!isset($paths)) {
            $this->partialViews = new ArrayList();
        } else if(is_array($paths)) {
            $this->partialViews = new ArrayList($paths);
        } else if($paths instanceof ArrayList){
            $this->partialViews = $paths;
        }
        
        $this->browserContext = $browserContext;
        
        $this->initPartialViews();
        
        
    }

    public function initPartialViews() {
        if(isset($this->browserContext)) {
            $context = "";
            if($this->browserContext->isMobile()) {
                $context = "mobile/";
            } else if($this->browserContext->isTablet()) {
                $context = "tablet/";
            }
            $this->partialViews->add('src/views/{0}/'.$context.'_default.phtml');
            $this->partialViews->add('src/views/{0}/'.$context.'{1}.phtml');
            $this->partialViews->add('src/Share/'.$context.'{1}.phtml');
        }
        
        $this->partialViews->add('src/views/{0}/_default.phtml');
        $this->partialViews->add('src/views/{0}/{1}.phtml');
        $this->partialViews->add('src/Share/{1}.phtml');
    }

    public function addPartialViewLocation($locationString) {
        $this->partialViews->add($locationString);
    }

    public function getPartialViewLocations() {
        return $this->partialViews;
    }

    public function renderResult(IViewResult $result, $controller, $action) {
        $viewFileExists = false;
        $triedViewFiles = new ArrayList();

        $output = "";

        foreach($result->getHeaders()->getIterator() as $field => $value) {
            header($field.": ".$value);
        }

        if($result->getViewFile() != null) {
            if(is_file($result->getViewFile())) {
                $viewFileExists = true;

            } else {
                $triedViewFiles->add($result->getViewFile());
            }
        } else {
            // Loop through view file conventions

            foreach($this->partialViews->getIterator() as $file) {
                $file = str_replace(array("{0}", "{1}"), array($controller, $action), $file);
                if(is_file($file)) {
                    $viewFileExists = true;
                    $result->setViewFile($file);
                    break;
                } else {
                    $triedViewFiles->add($file);
                }
            }
        }

        if($viewFileExists) {

            $output = $result->render();


        } else {
            foreach($triedViewFiles->getIterator() as $file) {
                $output .= $file."\n";
            }
        }

        return $output;
    }
}