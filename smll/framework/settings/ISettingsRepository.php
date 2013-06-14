<?php
interface ISettingsRepository {
	public function get($setting);
	public function __construct(ISettingsLoader $loader);
	public function load();
}