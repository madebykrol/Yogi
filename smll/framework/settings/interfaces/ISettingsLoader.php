<?php
namespace smll\framework\settings\interfaces;
interface ISettingsLoader {
    /**
     * return HashMap
     */
    public function getSettings();
}