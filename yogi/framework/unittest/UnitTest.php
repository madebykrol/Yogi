<?php
namespace yogi\framework\unittest;

abstract class UnitTest {
	
	private $tests = array();
	private $passed = 0;
	private $failed = 0;
	
	public function setup() {
		
	}
	
	protected function assert($boolean) {
		$trace = debug_backtrace();
		
		$status = 'Failed';
		if($boolean) {
			$status = 'Passed';
			$this->passed++;
		} else {
			$this->failed++;
		}
		
		$this->tests[] = array('test' => $trace[1]['class'].":".$trace[1]['function'], 'status' => $status);
		
		
	}
	
	protected function assertFalse($boolean) {
		
	}
	
	public function report () {
		$this->tests['passed'] = $this->passed;
		$this->tests['failed'] = $this->failed;
		return $this->tests;
	}
}