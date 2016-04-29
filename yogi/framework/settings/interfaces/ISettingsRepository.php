<?php
namespace yogi\framework\settings\interfaces;
use yogi\framework\settings\interfaces\ISettingsLoader;
interface ISettingsRepository {
	public function get($setting);
	public function __construct(ISettingsLoader $loader);
	public function load();
}