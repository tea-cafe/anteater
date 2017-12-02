<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 申请提现
 */
class ApplyTakeMoney extends MY_Controller{
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 1.查询财务认证状态
	 * 2.查询余额多少
	 */
	public function check(){
		$this->load->model('User');
		$account = $this->User->checkLogin();
		if(empty($account)){
			$account = '2494591314@qq.com';
		}else{
			$account = $account['email'];
		}
		$this->load->model('Finance');
		
		/* 查询账号财务信息状态 */
		$accStatus = $this->Finance->checkFinanceInfo($account);
		if(!$accStatus){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'财务信息未认证,请认证');
		}

		/*查询账号余额*/
		$accMoney = $this->Finance->getAccountMoney($account);
		if(empty($accMoney)){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'提现失败,请稍后重试');
			
		}
			
		if($accMoney['status'] !== 1){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'账户余额已被冻结,请联系客服');
		}

		if($accMoney['money'] < (float)100){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'账户余额低于100元,无法提现');
			
		}

		$this->load->helper('createkey');
		$token = keys(16);
		$data['token'] = md5($token);

		$RdsKey = 'ApplyTakeMoney_'.$account;
		$RdsValue['email'] = $account;
		$RdsValue['token'] = $token;
		$RdsValue['money'] = $accMoney['money'];
		
		$this->load->library('RedisUtil');
		$this->redisutil->set($RdsKey,serialize($RdsValue));
		$this->redisutil->expire($RdsKey,2*60);

		return $this->outJson($data,ErrCode::OK,'提现待确认');
	}

	public function confirm(){
		$getToken = $this->input->get('token',true);
		
		if(empty($getToken) || strlen($getToken) != 32){
			return $this->outJson('2',ErrCode::ERR_INVALID_PARAMS,'操作有误,请重新申请提现');
		}

		$this->load->model('User');
		$account = $this->User->checkLogin();
		if(empty($account)){
			$account = '2494591314@qq.com';
		}else{
			$account = $account['email'];
		}
		$RdsKey = 'ApplyTakeMoney_'.$account;
		
		$this->load->library('RedisUtil');
		$RdsValue = unserialize($this->redisutil->get($RdsKey));
		
		if(empty($RdsValue) || count($RdsValue) == 0){
			return $this->outJson('1',ErrCode::ERR_INVALID_PARAMS,'操作有误,请重新申请提现');
		}
		
		$RdsToken = md5($RdsValue['token']);
		if($getToken === $RdsToken){
			$this->load->model('Finance');
			$result = $this->Finance->confirmTakeMoney($account,$RdsValue['money']);
			if($result){
				return $this->outJson('',ErrCode::OK,'提现成功,待审核');
			
			}else{
				return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'操作有误,请重新申请提现');
			}
		}else{
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'操作有误,请重新申请提现');
		}
	}

}

?>
