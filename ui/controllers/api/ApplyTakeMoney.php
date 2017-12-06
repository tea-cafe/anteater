<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 申请提现
 */
class ApplyTakeMoney extends MY_Controller{
	public function __construct(){
		parent::__construct();
        $this->load->model('User');
        $this->arrUser = $this->User->checkLogin();
    }
	
	public function confirm(){
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }

        $account_id = $this->arrUser['account_id'];
        $email = $this->arrUser['email'];
        
        $this->load->model('Finance');
		
		/* 查询账号财务信息状态 */
		$accStatus = $this->Finance->checkFinanceInfo($account_id);
		if(empty($accStatus)){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'财务信息未认证,请认证');
		}

		/*查询账号余额*/
		$accMoney = $this->Finance->getAccountMoney($account_id);
		if(empty($accMoney)){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'提现失败,请稍后重试');
		}
			
		if($accMoney['status'] !== 1){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'账户余额已被冻结,请联系客服');
		}

		if($accMoney['money'] < (float)100){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'账户余额低于100元,无法提现');
		}

        $result = $this->Finance->confirmTakeMoney($account_id,$email,$RdsValue['money']);
        
        if($result){
            return $this->outJson('',ErrCode::OK,'提现成功,待审核');
        }else{
            return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'提现失败,请重新申请提现');
        }
	}

}

?>
