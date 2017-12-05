<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 重置密码
 */
class AccountResetPasswd extends CI_Controller{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		//header("Content-Type:application/json");
		//$email = $this->input->post("email",true);
		$email = $this->input->get("email",true);

		if(empty($email) || !stristr($email,"@")){
			
			$data['code'] = 2;
			$data['msg'] = '参数错误';
			$data['data']['email'] = $email;
			
			echo json_encode($data);
			return false;
		}

		$this->load->model("Account");
		$result = $this->Account->resetPwdCode($email);
		
		if($result === 2){
			$data['code'] = 2;
			$data['msg'] = '没有此账号';
			$data['data']['email'] = $email;
		}else if(!$result){
			$data['code'] = 2;
			$data['msg'] = '邮件发送失败,请重试';
			$data['data']['email'] = $email;
		}else{
			$data['code'] = 0;
			$data['msg'] = '邮件发送成功';
			$data['data']['email'] = $email;
		}

		//echo json_encode($data);
		//return false;
	}

	public function CheckCode(){
		$VerifyCode = $this->input->post("verifycode",true);
		$email = $this->input->post("email",true);

		if(empty($VerifyCode) || empty($email) || !stristr($email,'@')){
			$data['code'] = 2;
			$data['msg'] = '参数错误';
			$data['data'] = '';

			echo json_encode($data);
			return false;
		}

		$this->load->library("RedisUtil");
		$RdsKey = 'ResetPwd_'.$email;
		$RdsValue = $this->redisutil->get($RdsKey);
		$RdsValue = unserialize($RdsValue);

		if($VerifyCode == $RdsValue['code'] && $email == $RdsValue['email']){
			$data['code'] = 0;
			$data['msg'] = '验证码正确';
			$data['data'] = '';
		
			$RdsValue['status'] = 'ok';
			$this->redisutil->set($RdsKey,serialize($RdsValue));
			$this->redisutil->expire($RdsKey,60*5);
		}else{
			$data['code'] = 2;
			$data['msg'] = '验证码错误';
			$data['data'] = '';
		
		}

		echo json_encode($data);
		return false;
	}

	public function ModifyPwd(){
		$email = $this->input->post("email",true);
		$newPwd = $this->input->post("newpwd",true);
		$confirmPwd = $this->input->post("confirmpwd",true);

		if(empty($email) || empty($newPwd) || empty($confirmPwd)){
			$data['code'] = 2;
			$data['msg'] = '参数错误';
			$data['data'] = '';		
		}
		
		if($newPwd !== $confirmPwd){
			$data['code'] = 2;
			$data['msg'] = '密码输入不一致';
			$data['data'] = '';

			echo json_encode($data);
			return false;
		}
		$this->load->library("RedisUtil");
		$RdsKey = 'ResetPwd_'.$email;
		$RdsValue = $this->redisutil->get($RdsKey);
		$RdsValue = unserialize($RdsValue);
		if($RdsValue['status'] == 'ok'){
			$this->load->model('Account');
			$result = $this->Account->UpdatePwd($email,$newPwd,$confirmPwd);
			if($result){
				$data['code'] = 0;
				$data['msg'] = '密码重置成功';
				$data['data'] = '';
				echo json_encode($data);
				return false;
			}else{
				$data['code'] = 2;
				$data['msg'] = '密码重置失败';
				$data['data'] = '';
				
				echo json_encode($data);
				return false;
			}
		}else{
			$data['code'] = 2;
			$data['msg'] = '请重新获取验证码';
			$data['data'] = '';

			echo json_encode($data);
			return false;
		}
	}
}
?>
