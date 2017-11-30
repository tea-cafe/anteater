<?php
/**
 * 用户注册接口
 * szishuo
 */
class AccountRegister extends MY_Controller {

    const VALID_ACCOUNT_BASE_KEY = [
        'email', 
        'passwd',
        'phone', 
        'company',
        'contact_person',
    ]; 
        
    const VALID_ACCOUNT_COMPANY_FINANCE_KEY = [
        'financial_object',
        'contact_address',
        'bussiness_license_num',
        'bussiness_license_pic',
        'account_open_permission',
        'bank',
        'account_holder',
        'city',  
        'bank_branch',
        'bank_account',
        'remark',
    ]; 

    const VALID_ACCOUNT_PERSIONAL_FINANCE_KEY = [
        'financial_object',
        'contact_address',
        'account_holder',
        'identity_card_num',
        'identity_card_front',
        'identity_card_back',
        'bank',
        'city',  
        'bank_branch',
        'bank_account',
        'remark', 
    ]; 

    public function __construct() {
        parent::__construct();
    }

    /**
     * 基本信息注册
     */
    public function index() {//{{{//
        $arrPostParams = $this->input->post();
        if (empty($arrPostParams)
            || count($arrPostParams) !== count(self::VALID_ACCOUNT_BASE_KEY)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        // TODO 各种号码格式校验
        foreach ($arrPostParams as $key => &$val) {
            if(!in_array($key, self::VALID_ACCOUNT_BASE_KEY)) {
                return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
            }
            $val = $this->security->xss_clean($val);
        }
        $arrPostParams['passwd'] = md5($arrPostParams['passwd']);

        // 入库
        $this->load->model('Account');
        $arrRes = $this->Account->insertAccountBaseInfo($arrPostParams);

        if ($arrRes['code'] === 0) {
            $this->load->model('User');
            $this->User->doLogin($arrPostParams['email'], md5($arrPostParams['passwd']));
            return $this->outJson('', ErrCode::OK, '注册成功');
        }
        if ($arrRes['code'] === 1062) {
            return $this->outJson('', ErrCode::ERR_DUPLICATE_ACCOUNT);
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//

    /**
     * 财务信息注册
     */
    public function updateFinanceInfo() {//{{{//
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN); 
        }
        $arrPostParams = $this->input->post();
        $arrValidKeys = $arrPostParams['financial_object'] == '公司' ? self::VALID_ACCOUNT_COMPANY_FINANCE_KEY : self::VALID_ACCOUNT_PERSIONAL_FINANCE_KEY;
        if (empty($arrPostParams)
            || count($arrPostParams) !== count($arrValidKeys)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        foreach ($arrPostParams as $key => &$val) {
            if(!in_array($key, $arrValidKeys)) {
                return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
            }
            $val = $this->security->xss_clean($val);
        }

        $arrPostParams['where'] = 'account_id=' . $this->arrUser['account_id'];

        $this->load->model('Account');
        $bolRes = $this->Account->updateAccountFinanceInfo($arrPostParams);

        if ($bolRes) {
            return $this->outJson('', ErrCode::OK, '财务信息提交成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM, '财务信息提交失败');
    }//}}}//

}
