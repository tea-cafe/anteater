<?php
/**
 * 后台登陆
 */
class FunBgUser extends CI_Model {
	public function __construct(){
		parent::__construct();
	}

	public function doLogin($userName,$passWord){
		$params = array(
			'select' => '',
			'where' => 'username = "'.$userName.'"'.' AND password = "'.md5($passWord).'"',
			'order_by' => '',
			'limit' => '',
		);
		$this->load->library('DbUtil');
		$userRes = $this->dbutil->getBgUser($params);

		if(empty($userRes)){
			return false;
		}
		$_SESSION['login_time'] = time();
		$_SESSION['bg_account_id'] = $userRes[0]['id'];
		$_SESSION['username'] = $userRes[0]['username'];
		$_SESSION['password'] = $userRes[0]['password'];
		return true;

	}
}
?>
