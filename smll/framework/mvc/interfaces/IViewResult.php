<?php
namespace smll\framework\mvc\interfaces;
interface IViewResult {
    public function render();
    /**
     * @return HashMap
    */
    public function getHeaders();
}