<?php
class ContactModel {
	
	/**
	 * @var String
	 * [FormField]
	 * [InputType=text]
	 * [DefaultValue=]
	 * [Label=Name]
	 * [Placeholder=Name]
	 * [Required]
	 */
	public $name;
	
	/**
	 *
	 * @var String
	 * [FormField]
	 * [InputType=text]
	 * [DefaultValue=]
	 * [Label=Email]
	 * [ValidationPattern(Pattern=[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?,ErrorMessage=Must be a valid Email adress)]
	 * [StringLength(MaxLength=0,ErrorMessage=Email must be longer than 6 characters, MinLength=6)]
	 * [Placeholder=Email]
	 */
	public $email;
	
	/**
	 *
	 * @var String
	 * [FormField]
	 * [InputType=textarea]
	 * [Wysiwyg]
	 * [DefaultValue=]
	 * [Label=Message]
	 */
	public $message;
	
	
}