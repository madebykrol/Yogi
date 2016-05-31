<?php
/**
 * Very very very basic implementation of a unit test framework.
 * 
 */
include('yogi/YogiClassLoader.php');

use yogi\YogiClassLoader;
use yogi\framework\unittest\UnitTest;

$autoloader = new YogiClassLoader();
$autoloader->register();

$shortopts  = "t:";
$shortopts .= "R:"; 
$shortopts .= "v"; 
$shortopts .= '?';

$longopts  = array(
		"test:", 
		"report:",  
		"verbose",
		"help",
);
$options = getopt($shortopts, $longopts);

$verbose = false;;
$report = null;
$starttime = microtime(true);
$endtime = null;
$errors = 0;
$errf = array();

// set to the user defined error handler
$old_error_handler = set_error_handler("_yogiUnitErrorHandler");

// Call main method.. why? BECAUSE!
main($options);


/**
 * Running through all tests specified by the t|test argument
 * @param array $args
 */
function main($args) {
	global $verbose;
	global $report;
	global $endtime;
	global $errors;
	global $errf;
	$error = false;
	$test = null;
	$report = null;
	$verbose = false;
	$help = false;
	
	if(!isset($args['t']) && !isset($args['test'])) {
		$error = true;
	} else {
		$test = isset($args['t']) ? $args['t'] : $args['test']; 
	}
	
	if(isset($args['R'])) {
		$report = $args['R'];
	} else if(isset($args['report'])) {
		$report = $args['report'];
	}
	
	if(isset($args['v'])) {
		$verbose = !$args['v'];
	} else if(isset($args['verbose'])) {
		$verbose = !$rgs['verbose'];
	}
	$testReport = array();
	if(is_file($test)) {
		if($verbose) {
			print "preparing to run unit test for: ".$test."\n";
		}
		$testReport[$test] = _yogiUnit($test);
		$testReport[$test]['errors'] = $errors;
		$errors = 0;
		
		$testReport[$test]['error_report'] = $errf;
		$errf = array();
	} else if(is_dir($test)) {
		if($verbose) {
			print "preparing to run unit tests in directory: ".$test."\n";
		}
		$handle = opendir($test);
		
		/* This is the correct way to loop over the directory. */
		while (false !== ($entry = readdir($handle))) {
			if($entry != "." && $entry != ".." && strpos($entry, "Test") !== false) {
				$testReport[$entry] = _yogiUnit($test."/".$entry);
				$testReport[$entry]['errors'] = $errors;
				$errors = 0;
				
				$testReport[$entry]['error_report'] = $errf;
				$errf = array();
			}
		}
	}
	
	$endtime = microtime(true);
	_generateReport($testReport);
	
	if($error || $help) {
		die("Usage: -t|test <Unit testfile|Directory>\tA unittest class or a directory with UnitTest classes..\n [-R|report <filename>]\tWhen specifying a report file, YogiUnit will generate a HTML file with the test report\n [-v|verbose]");
	}
}

function _generateReport($testReport) {
	
	if($testReport != null) {
		_generateHTMLReport($testReport);
	} else {
		_generateCLIOutput($testReport);
	}
	
}

function _generateHTMLReport($testReport) {
	global $report;
	global $verbose;
	global $starttime;
	global $endtime;
	
	$report = str_replace(array("{Y}", "{m}", "{d}"), array(date("Y"), date("m"), date("d")), $report);
	if($verbose) {
		print "Generating report: ".$report."\n";
	}
	
	$output = "<html>\n";
	$output .= "\t<head>\n";
		 $output .= "\t\t<title>Yogi framework Unit test report ".date("Y-m-d", time())."</title>\n";
		 $output .= "\t\t<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css\">";

	$output .= '<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>';
	$output .= '<script>';
		$output .= '
				$(document).ready(function() {
					$(\'.error-toggle\').on(\'click\', function(event) {
						error = $(this).data(\'show-error\');
						$(\'#\'+error).toggle();
					});
				});
				';
	$output .= '</script>';
	$output .= "\t</head>\n";
	$output .= "\t<body style=\"margin-top: 20px;\">\n";
	$output .= "\t\t<div class=\"wrapper container\">\n";
	
	$totPassed = 0;
	$totFailed = 0;
	$totErrors = 0;
	
	foreach($testReport as $test => $summary) {
		
		$passed = $summary['passed'];
		$totPassed += $passed;
		$failed = $summary['failed'];
		$totFailed += $failed;
		$errors = $summary['errors'];
		$totErrors += $errors;
		
	}
	
	
	
	$output .= "<div class=\"row\"><div class=\"span6\">
				<div class=\"well\">
					<h1>Yogi unit test report</h1> <br/>
					<strong>Start time: </strong>".date("Y-m-d H:i:s", $starttime)."<br />
					<strong>Duration: </strong>".sprintf("%.4f", ($endtime-$starttime))." seconds <br />
					<strong>Report: </strong> Passed: ".$totPassed." Failed: ".$totFailed." Errors: ".$totErrors."
						</div>
						</div></div>";
	$output .= "<div class=\"row\"><div class=\"span12\">";
	
	$totPassed = 0;
	$totFailed = 0;
	$totErrors = 0;
	$i = 0;
	foreach($testReport as $test => $summary) {
		
		
		$passed = $summary['passed'];
		$totPassed += $passed;
		$failed = $summary['failed'];
		$totFailed += $failed;
		$errors = $summary['errors'];
		$totErrors += $errors;
		
		
		$errorReport = $summary['error_report'];
		
		
		$output .= "<table class=\"table\">";
		
		unset($summary['failed']);
		unset($summary['passed']);
		unset($summary['errors']);
		unset($summary['error_report']);
		
		$output .= "<thead>";
		$output .= "<tr>";
			$output .= "<th width=\"75%\">Test</th><th>Passed</th><th>Failed</th><th>Errors</th>";
		$output .= "</tr>";
		$output .= "</thead><tbody>";
		$class="success";
		if($failed > 0 || $errors > 0) {
			$class = "warning";
		}
		if($passed == 0) {
			$class = "danger";
		}
		$output .= "<tr class=\"".$class."\">";
		
		$btn = "";
		if($errors > 0) { $btn = "<a href=\"#\" data-show-error=\"error-".$i."\" class=\"btn btn-mini btn-danger error-toggle\">Report</a>"; }
		
			$output .= "<td><strong>".$test."</strong></td><td>".$passed."</td><td>".$failed."</td><td>".$errors.$btn."</td>";
		$output .= "</tr>";
		$output .= '<tr>';
			$output .= '<td colspan="4" style="display: none" id="error-'.$i.'">';
				foreach($errorReport as $r) {
					$output .= '<div class="row" style="font-size: 1em;"><div class="span12">';
							$output .= '<strong>'.$r['errstr'].'</strong>';
							$output .= '<p>';
								$output .= '<strong>Line: </strong>'.$r['errline'].'<br />';
								$output .= '<strong>File: </strong>'.$r['errfile'].'<br />';
							$output .= '</p>';
					$output .= '</div></div>';
				}
			$output .= '</td>';
		$output .= '</tr>';
		$output .= "<tbody></table>";
		$i++;
	}
	foreach($testReport as $test => $summary) {
		$passed = $summary['passed'];
		$totPassed += $passed;
		$failed = $summary['failed'];
		$totFailed += $failed;
		$errors = $summary['errors'];
		
		unset($summary['failed']);
		unset($summary['passed']);
		unset($summary['errors']);
		unset($summary['error_report']);
		
		$output .= "<table class=\"table\"><thead>";
		$output .= "<tr>";
			$output .= '<th width="100%" colspan=\"2\">'.$test.'</th><th width="50px">Status</th>';
		$output .= "</tr>";
		$output .= "</thead>";
		$output .= "<tbody>";
		
		foreach($summary as $d) {
			$class="danger";
			if($d['status'] == "Passed") {
				$class = "success";
			}
			$output .= "<tr class=\"".$class."\">";
			
			$test = explode(":", $d['test']);
			if(count($test) > 1 ) {
				$output .= "<td>".$test[1]."</td>";
			} else {
				$output .= "<td>".$test[0]."</td>";
			}
				$output .= "<td>".$d['status']."</td>";
			$output .= "</tr>";
		}
		$output .= "</tbody>";
		$output .= "</table>";
	}
	
	
	
	$output .="\t\t</div></div></div>\n";
	$output .="\t<body>\n";
	$output .= "</html>\n";
	if(($handle = fopen($report, 'w+')) !== FALSE) {
		fwrite($handle, $output);
		fclose($handle);
	} else {
		print "Could not open file (".$report.") for writing\n";
	}
}

function _generateCLIOutput($testReport) {
	$output = "";
	$totPassed = 0;
	$totFailed = 0;
	foreach($testReport as $test => $summary) {
		$passed = $summary['passed'];
		$totPassed += $passed;
		$failed = $summary['failed'];
		$totFailed += $failed;
	
		unset($summary['failed']);
		unset($summary['passed']);
	}
	
	print $output;
}

function _getClass($test) {
	$class = str_replace(".php", "", $test);
	$class = explode("/", $class);
	$class = $class[count($class)-1];
	
	return $class;
}

function _yogiUnit($test) {
	include($test);
	// strip .php and parse out from path
	
	$class = _getClass($test);
	
	$obj = new $class();
	if($obj instanceof UnitTest) {
		$obj->setup();
		$rClass = new ReflectionClass($class);
		
		foreach($rClass->getMethods() as $method) {
			if($method instanceof ReflectionMethod) {
				if(strpos($method->getName(), "test") !== FALSE) {
					$method->invoke($obj);
				}
			}
		}
	} else {
		print $test . " Is not extending the UnitTest baseclass\n";
	}
	
	return $obj->report();
} 

function _yogiUnitErrorHandler($errno, $errstr, $errfile, $errline) {
	global $errors;
	global $errf;
	$errors++;
	$errf[] = array('errno' => $errno, 'errstr' => $errstr, 'errfile' => $errfile, 'errline' => $errline);
}

