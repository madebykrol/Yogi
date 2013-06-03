<?php
interface IModelState {
	
	/**
	 * Returns or sets wether the model is valid or not
	 * @param optional boolean
	 * @return bool
	 */
	public function isValid($state = null);
	
}