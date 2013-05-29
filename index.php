<?php
include('smll/AutoLoader.php');
include('src/Application.php');

$dic = new ContainerBuilder();
$dic->register('Session', 'ISession');
$dic->register('ViewEngine', 'IViewEngine');
$dic->register('XmlSettingsLoader', 'ISettingsLoader')
	->addArgument("Manifest.xml");

$dic->register('ControllerFactory', 'IControllerFactory');
$dic->register('ViewFactory', 'IViewFactory');
$dic->register('ActionFilterConfig', 'IActionFilterConfig')
	->inRequestScope();

$dic->register('Request', 'IRequest')
	->addArgument($_SERVER)
	->addArgument($_GET)
	->addArgument($_POST)
	->addMethodCall('init');

$dic->register('Router', 'IRouter')
	->set('RouterConfig', new RouterConfig())
	->addMethodCall('init');

$dic->register('Settings', 'ISettings')
	->addMethodCall('load')
	->inRequestScope();

$dic->register('Application', 'IApplication')
	->addArgument(null)
	->addArgument(null)
	->addArgument(new Service('ISettings'));

$application = $dic->get('IApplication');
$application->run();
