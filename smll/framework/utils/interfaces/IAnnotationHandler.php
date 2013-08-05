<?php
namespace smll\framework\utils\interfaces;
use smll\framework\utils\HashMap;

interface IAnnotationHandler {
    /**
     * @return array;
     * @param unknown $m
     */
    public function getAnnotations($m);
    public function hasAnnotation($annotation, $m);
    public function getAnnotation($annotation, $m);
    public function parseAnnotation($annotation);
}