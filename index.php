<?php
session_start();
$start = (float) array_sum(explode(' ',microtime()));
#
// PHP code whose execution time you want to measure
include('smll/AutoLoader.php');
include('src/Application.php');

$dic = new ContainerBuilder();
$dic->register('AnnotationHandler', 'IAnnotationHandler')->inRequestScope();
$dic->register('FormFieldHandler', 'IFormFieldHandler');
$dic->register('ModelBinder', 'IModelBinder')->inRequestScope();

$dic->register('FilterConfig', 'IFilterConfig')
	->inRequestScope();

$dic->register('Request', 'IRequest')
	->addArgument($_SERVER)
	->addArgument($_GET)
	->addArgument($_POST)
	->addMethodCall('init');

$dic->register('Router', 'IRouter')
	->set('RouterConfig', new RouterConfig())
	->addMethodCall('init');

$dic->register('Application', 'IApplication')
	->addArgument(null)
	->addArgument(null)
	->addMethodCall('init')
	->set('ModelBinder', new Service('IModelBinder'));

$application = $dic->get('IApplication');
$application->run();
#
$end = (float) array_sum(explode(' ',microtime()));
//print "Processing time: ". sprintf("%.4f", ($end-$start))." seconds.";