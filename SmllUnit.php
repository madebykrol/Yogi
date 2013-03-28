#!/usr/bin/php -q
<?php
include ('smll/framework/cli/CLI.php');
include ('smll/framework/cli/SmllUnitTest.php');


$test;
$cases;
$includePath;
CLI::getArgs(array(array(&$test), array(&$cases), array(&$includePath, 'smll/tests/')));


include($includePath.$test.'.php');
$test = new $test();
if(isset($cases)) {
	$cases = explode(",", $cases);
	foreach($cases as $case) {
		$test->runTest($case);
	}
} else {
	throw new Exception(); /** @todo Implement actuall exception */
}

$test->report();