<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 账户信息 
 */

class AccountInfo extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

	/**
	 */
	public function index()
	{
        $this->load->model('Account');
        $arrData = $this->Ads->getAccountInfo();

        echo '这是账户信息页面';exit;
        $this->load->library('Smartylib');
        $this->smartylib->assign('name', $name);
        $this->smartylib->display('demo.tpl');
	}
}
