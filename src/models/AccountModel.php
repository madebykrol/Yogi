<?php
namespace src\models;
class AccountModel {
	/**
	 * [FormField]
	 * [Label=Username]
	 * [InputType=text]
	 * [Required]
	 * [ErrorMessage=You need to input a valid user]
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
}