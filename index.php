<?php
include('smll/AutoLoader.php');
include('src/MvcApplication.php');

$dic = new ContainerBuilder();
$dic->register('Session', 'ISession');

$dic->register('ControllerFactory', 'IControllerFactory');
$dic->register('Request', 'IRequest')
	->addArgument($_SERVER)
	->addArgument($_POST);
$dic->register('Router', 'IRouter')
	->addArgument(new Service('IControllerFactory'))
	->addMethodCall('init');

$dic->register('MockSettings', 'ISettings');

$dic->register('MvcApplication', 'IApplication')
	->addArgument(null)
	->addArgument(null)
	->addArgument(new Service('ISettings'));

$application = $dic->get('IApplication');
$application->run();