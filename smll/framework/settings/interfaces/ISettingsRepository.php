<?php
namespace smll\framework\settings\interfaces;
use smll\framework\settings\interfaces\ISettingsLoader;
interface ISettingsRepository {
	public function get($setting);
	public function __construct(ISettingsLoader $loader);
	public function load();
}