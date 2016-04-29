<?php
namespace yogi\framework\exceptions;
use \Exception;
use \PDOStatement;
/**
 * A Query Exception
 * Thrown whenever an error has occured related to a query.
 * @author Kristoffer "mbk" Olsson
 *
 */
class QueryException extends Exception {
	public function __construct(PDOStatement $statement) {
		$errorInfo = $statement->errorInfo();
		parent::_construct("State: ".$errorInfo[0]." Code: ".$errorInfo[1]." Message: ".$errorInfo[2]);
	}
}