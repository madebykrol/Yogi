<?php
include('yogi/YogiClassLoader.php');
use yogi\framework\utils\AnnotationHandler;
use yogi\framework\di\ContainerBuilder;
use yogi\modules\DefaultContainerModule;
use yogi\YogiClassLoader;


$start = (float) array_sum(explode(' ',microtime()));

$autoloader = new YogiClassLoader();
$autoloader->register();


$dic = new ContainerBuilder(new AnnotationHandler());
$dic->loadModule(new DefaultContainerModule());


$application = $dic->get('yogi\framework\IApplication');
$application->setContainer($dic);
$application->init();
$application->run();
$application->close();
#
$end = (float) array_sum(explode(' ',microtime()));
//print "Processing time: ". sprintf("%.4f", ($end-$start))." seconds.";