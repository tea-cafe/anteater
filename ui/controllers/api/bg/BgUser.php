<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 后台 登陆
 */

class BgUser extends MY_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function Login(){
		$userName = $this->input->post("username");
		$passWord = $this->input->post("password");
		$this->load->model('bg/FunBgUser');
		$loginRes = $this->FunBgUser->doLogin($userName,$passWord);
		
		if($loginRes){
			return $this->outJson('',ErrCode::OK,'登陆成功');
		}else{
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'登陆失败');
		}
	}
}
?>
