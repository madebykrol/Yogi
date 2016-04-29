<?php
namespace yogi\framework\exceptions;
use \Exception;
/**
 * A Access Denied Exception
 * Usefull in scenarios when you need to alert either your application or users 
 * that permission to some parts is prohibited
 * @author Kristoffer "mbk" Olsson
 *
 */
class AccessDeniedException extends Exception {
	
}