<?php
namespace smll\framework\mvc\filter;
use smll\framework\utils\interfaces\IAnnotationHandler;

class FilterAttribute {

    protected $annotations;

    protected $annotationHandler = null;

    public function __construct(IAnnotationHandler $annotationHandler) {
        $this->annotationHandler = $annotationHandler;
    }

    public function setAnnotations($annotations) {
        $this->annotations = $annotations;
    }

    public function getAnnotations() {
        return $this->annotations;
    }
}