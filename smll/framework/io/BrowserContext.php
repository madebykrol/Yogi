<?php
namespace smll\framework\io;

use smll\framework\io\interfaces\IBrowserContext;

require 'smll/lib/MobileDetect/Mobile_Detect.php';

class BrowserContext implements IBrowserContext
{
    /**
     * @var \Mobile_Detect
     */
    private $detect = null;
    
    public function __construct()
    {
        $this->detect = new \Mobile_Detect();
    }
    
    public function isMobile()
    {
        return ($this->detect->isMobile() && !$this->detect->isTablet());
    }
    
    public function isTablet()
    {
        return $this->detect->isTablet();
    }
    
    public function getVersion()
    {
        
    }
}