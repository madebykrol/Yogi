<?php
namespace src\models;
class RegisterModel {
	
	/**
	 * [FormField]
	 * [Label=Username]
	 * [InputType=text]
	 * [Required]
	 * [StringLength(MinLength=3)]
	 * [ErrorMessage=Must be a atleast 3 chars long]
	 * @var unknown
	 */
	public $username;
	
	/**
	 * [FormField]
	 * [Label=Password]
	 * [InputType=password]
	 * [Required]
	 * @var unknown
	 */
	public $password;
	
	/**
	 * [FormField]
	 * [Label=Confirm password]
	 * [InputType=password]
	 * [MatchField(password)]
	 * @var unknown
	 */
	public $confirmPassword;
	
	/**
	 * [FormField]
	 * [Label=Email]
	 * [InputType=text]
	 * [Required]
	 * [ValidationPattern(Pattern=[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9]))]
	 * [ErrorMessage=Must be a valid email-address]
	 * @var unknown
	 */
	public $email;
	
}