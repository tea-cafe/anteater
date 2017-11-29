<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 接口 用户信息
 */

class AccountInfo extends MY_Controller {

    /*
     *
     */
    public function __construct() {
        parent::__construct();
    }

	/**
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $this->load->model('Account');
        $arrAccountInfo = $this->account->getInfo();
        $this->outJson($arrAccountInfo, ErrCode::OK);
	}
}
