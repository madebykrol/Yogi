<?php
class ContactModel {
	
	/**
	 * @var String
	 * [FormField]
	 * [InputType=text]
	 * [DefaultValue=]
	 * [Label=Name]
	 * [Placeholder=Name]
	 */
	public $name;
	
	/**
	 *
	 * @var String
	 * [FormField]
	 * [InputType=text]
	 * [DefaultValue=]
	 * [Label=Email]
	 * [Placeholder=Email]
	 */
	public $email;
	
	/**
	 *
	 * @var String
	 * [FormField]
	 * [InputType=textarea]
	 * [Wysiwyg]
	 * [DefaultValue=...]
	 * [Label=Message]
	 */
	public $message;
	
	
}