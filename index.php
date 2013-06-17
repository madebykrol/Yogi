<?php
include('smll/SmllClassLoader.php');
use smll\framework\di\ContainerBuilder;
use smll\framework\route\RouterConfig;
use smll\framework\di\Service;
use smll\SmllClassLoader;


session_start();
$start = (float) array_sum(explode(' ',microtime()));
#
// PHP code whose execution time you want to measure

$autoloader = new SmllClassLoader();
$autoloader->register();


$dic = new ContainerBuilder();
$dic->register('smll\framework\utils\AnnotationHandler', 'smll\framework\utils\interfaces\IAnnotationHandler')->inRequestScope();
$dic->register('smll\framework\utils\handlers\FormFieldHandler', 'smll\framework\utils\handlers\interfaces\IFormFieldHandler');
$dic->register('smll\framework\mvc\ModelBinder', 'smll\framework\mvc\interfaces\IModelBinder')->inRequestScope();

$dic->register('smll\framework\mvc\filter\FilterConfig', 'smll\framework\mvc\filter\interfaces\IFilterConfig')
	->inRequestScope();

$dic->register('smll\framework\io\Request', 'smll\framework\io\interfaces\IRequest')
	->addArgument($_SERVER)
	->addArgument($_GET)
	->addArgument($_POST)
	->addMethodCall('init');

$dic->register('smll\framework\route\Router', 'smll\framework\route\interfaces\IRouter')
	->set('RouterConfig', new RouterConfig())
	->addMethodCall('init');

$dic->register('src\Application', 'smll\framework\IApplication')
	->addArgument(null)
	->addArgument(null)
	->addMethodCall('init')
	->set('ModelBinder', new Service('smll\framework\mvc\interfaces\IModelBinder'));

$application = $dic->get('smll\framework\IApplication');
$application->run();
#
$end = (float) array_sum(explode(' ',microtime()));
print "Processing time: ". sprintf("%.4f", ($end-$start))." seconds.";