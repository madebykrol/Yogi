<?php
namespace smll\framework\system\controllers;
use smll\framework\mvc\Controller;
use smll\framework\system\models\SystemIndexViewModel;
use smll\framework\system\models\SystemInstallModel;
use smll\framework\system\ISystemModelFactory;
use smll\framework\settings\interfaces\ISettingsRepository;
use smll\framework\io\file\File;
use smll\framework\io\db\DB;

/**
 * 
 * @author ksdkrol
 *
 */
class SystemController extends Controller {
	
	private $settingsRepository;
	private $modelFactory;
	
	public function __construct(
			ISystemModelFactory $modelFactory,
			ISettingsRepository $settingsRepository) {
		$this->modelFactory = $modelFactory;
		$this->settingsRepository = $settingsRepository;
	}
	/**
	 * 
	 */
	public function index() {
		$model = new SystemIndexViewModel();
		
		return $this->view($model, "smll/framework/system/views/index.phtml");
	}
	
	
	public function install() {
		$model = new SystemInstallModel();
		// Install database.
		
		$systemSettings = $this->settingsRepository->get("system");
		
		if(!$systemSettings['installation']['installed']) {
			// lets install the system on the database.
			
			$smllDatabaseSqlFile = new File('smll/install/smll.sql');

			$smllSystemSql = $smllDatabaseSqlFile->readFileStream();
			
			
			$connectionStrings = $this->settingsRepository->get('connectionStrings');
			
			$db = new DB($connectionStrings['Default']['connectionString']);
			$db->query($smllSystemSql);
			
			$this->application->install();
			
			// if installation is completed successfully
			// We rewrite the manifestfile so that the installation procedure
			// wont run again.			
		}
		
		return $this->view($model, "smll/framework/system/views/install.phtml");
	}
	
}