<?php
namespace smll\cms\models;

class TaxonomyTermModel {
	
	/**
	 * [FormField]
	 * [InputType=hidden]
	 * [Required]
	 * @var int
	 */
	public $vocabulary;
	
	/**
	 * [FormField]
	 * [Label=Parent]
	 * [InputType=text]
	 * @var int
	 */
	public $parent;
	/**
	 * [FormField]
	 * [InputType=text]
	 * [Label=Term]
	 * [Required]
	 * @var int
	 */
	public $term;
	
	/**
	 * [FormField]
	 * [InputType=text]
	 * [Label=Short]
	 * [Required]
	 * @var int
	 */
	public $short;
	
	/**
	 * [FormField]
	 * [InputType=textarea]
	 * [Label=Description]
	 * [Required]
	 * @var int
	 */
	public $description;
	
	
	public $returnUrl;
	
}