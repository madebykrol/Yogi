<?php
namespace src;
use smll\framework\mvc\filter\OutputCacheFilter;

use smll\framework\route\Route;

use smll\cms\CmsApplication;

class Application extends CmsApplication {
	
	protected function applicationStart() {
		
		
		$this->filterConfig->addActionFilter(new OutputCacheFilter());
		
		/**
		 * Default route
		 */
		$this->routerConfig->mapRoute(
				new Route("Streams", "Streams/{game}",
						array(
								"controller" => "Stream",
								"action" => "index",
								"game" => Route::URLPARAMETER_REQUIRED)));
	}
	
}