<?php
namespace smll\cms\models;

class CmsEditUserModel {
	
	/**
	 * [FormField]
	 * [InputType=hidden]
	 * [Required]
	 * @var int
	 */
	public $userid;
	
	/**
	 * [FormField]
	 * [Label=Username]
	 * [InputType=text]
	 * [Required]
	 * @var string
	 */
	public $username;
	
	/**
	 * [FormField]
	 * [Label=Generate new password]
	 * [InputType=checkbox]
	 * [Required]
	 * @var boolean
	 */
	public $generateNewPassword;
	
	/**
	 * [FormField]
	 * [Label=E-mail]
	 * [InputType=text]
	 * [Required]
	 * @var string
	 */
	public $email;
	
	/**
	 * [FormField]
	 * [Label=Roles]
	 * [InputType=radiobutton]
	 * @var HashMap
	 */
	public $roles;
	
}