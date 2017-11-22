<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User');
    }

    public function _remap($method, $params = []) {
        if (isset($_GET['logout'])) {
            return $this->logout();
        }
        if (isset($_GET['login'])) {
            return $this->login();
        }
        $this->$method;

    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $arrData = [];
        $arrData['login'] = $this->User->checkLogin();
        if ($arrData['login']) {
            head("Location:  /", 302, true); // 跳转到媒体数据页
        }
        // index 提示页
	}

    public function login() {
        // 显示登录表单
    }

    public function logout() {
        $this->user->clearLoginInfo();
        header("Location: /", 302, true);
    }
}
