<?php
class SettingsRepository implements ISettingsRepository {
	
	private $loader;
	/**
	 * 
	 * @var HashMap;
	 */
	private $settings;
	
	public function __construct(ISettingsLoader $loader) {
		$this->loader = $loader;
	}
	
	public function load() {
		$this->settings = $this->loader->getSettings();
	}
	
	public function get($setting) {
		return $this->settings[$setting];
	}
}