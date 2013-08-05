<?php
namespace smll\framework\io\interfaces;

interface IBrowserContext
{
    /**
     * Check if the current browser context is a mobile device.
     * @return boolean
     */
    public function isMobile();
    
    
    /**
     * Check if the current browser context is a tablet device
     */
    public function isTablet();
    
    /**
     * Get the version of the current browser context, ie android 2.2.3
     */
    public function getVersion();
}