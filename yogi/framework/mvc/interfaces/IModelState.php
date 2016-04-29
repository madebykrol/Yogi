<?php
namespace yogi\framework\mvc\interfaces;
interface IModelState {
	
	/**
	 * Returns or sets wether the model is valid or not
	 * @param optional boolean
	 * @return bool
	 */
	public function isValid($state = null);
	
	/**
	 * Set error message for field
	 * @param String $name
	 * @param String $message
	 */
	public function setErrorMessageFor($name, $message);
}