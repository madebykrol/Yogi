<?php
class AccountController extends Controller {
	
	protected $session;
	
	public function __construct(ISession $session) {
		$this->session = $session;
	}
	
	/**
	 * [AuthenticationRequired]
	 */
	public function index() {
		return $this->view();
	}
	
	/**
	 * [AuthenticationRequired]
	 */
	public function logout() {
		
	}
	
	/**
	 * [AllowAnonymous]
	 */
	public function login() {
		$model = new LoginModel();
		return $this->view($model);
	}
	
	/**
	 * [AllowAnonymous]
	 */
	public function post_login(LoginModel $login) {
		
	}
}