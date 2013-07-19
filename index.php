<?php
include('smll/SmllClassLoader.php');
use smll\framework\di\ContainerBuilder;
use smll\cms\modules\CmsContainerModule;
use smll\SmllClassLoader;


session_start();
$start = (float) array_sum(explode(' ',microtime()));
$startMem = memory_get_usage(true);
#
// PHP code whose execution time you want to measure

$autoloader = new SmllClassLoader();
$autoloader->register();


$dic = new ContainerBuilder();
$dic->loadModule(new CmsContainerModule());


$application = $dic->get('smll\framework\IApplication');
$application->setContainer($dic);
$application->init();
$application->run();
#
$endMem = memory_get_usage(true);
$end = (float) array_sum(explode(' ',microtime()));
print "Processing time: ". sprintf("%.4f", ($end-$start))." seconds.";
print "<br />";
print "Memory usage - Start: ".convert($startMem)." Finnish: ".convert($endMem);
print "Peak memory usage: ".convert(memory_get_peak_usage(true));


function convert($size)
{
	$unit=array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}