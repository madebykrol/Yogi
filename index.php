<?php
include('smll/SmllClassLoader.php');
use smll\framework\di\ContainerBuilder;
use smll\modules\DefaultContainerModule;
use smll\SmllClassLoader;


$start = (float) array_sum(explode(' ',microtime()));
#
// PHP code whose execution time you want to measure

$autoloader = new SmllClassLoader();
$autoloader->register();


$dic = new ContainerBuilder();
$dic->loadModule(new DefaultContainerModule());


$application = $dic->get('smll\framework\IApplication');
$application->setContainer($dic);
$application->init();
$application->run();
#
$end = (float) array_sum(explode(' ',microtime()));
print "Processing time: ". sprintf("%.4f", ($end-$start))." seconds.";