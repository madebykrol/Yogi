<?php
namespace yogi\framework\exceptions;
use \Exception;

/**
 * Thrown when trying to create a user with the same username / email 
 * as another.
 * @author Kristoffer "mbk" Olsson
 *
 */
class MembershipUserExistsException extends Exception {
	
}