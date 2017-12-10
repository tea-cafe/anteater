<?php
/**
 * 用户注册接口
 * szishuo
 */
class AccountModify extends MY_Controller {

    const VALID_ACCOUNT_BASE_KEY = [
        'email', 
        'phone', 
        'contact_person',
    ]; 
        
    const VALID_ACCOUNT_COMPANY_FINANCE_KEY = [
        'financial_object',
        'collection_company',
        'contact_address',
        'bussiness_license_num',
        'bussiness_license_pic',
        'account_open_permission',
        'account_company',
		'bank',
        'city',  
        'bank_branch',
        'bank_account',
        'remark',
    ]; 

    const VALID_ACCOUNT_PERSIONAL_FINANCE_KEY = [
        'financial_object',
        'account_holder',
        'identity_card_num',
        'identity_card_front',
        'identity_card_back',
        'contact_address',
        'bank',
        'city',
        'bank_branch',
        'bank_account',  
        'remark', 
    ]; 

    public function __construct() {
        parent::__construct();
        $this->load->model('User');
        $this->arrUser = $this->User->checkLogin();
    }

    /**
     * 基本信息修改
     */
    public function index() {//{{{//
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }

        //$arrPostParams = json_decode(file_get_contents('php://input'), true);
        //$arrPostParams = $this->input->post();
        $arrPostParams = $this->input->get();
        unset($arrPostParams['company']);
        if (empty($arrPostParams) || count($arrPostParams) !== count(self::VALID_ACCOUNT_BASE_KEY)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        
        // TODO 各种号码格式校验
        foreach ($arrPostParams as $key => &$val) {
            if(!in_array($key, self::VALID_ACCOUNT_BASE_KEY)) {
                return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
            }
            $val = $this->security->xss_clean($val);
        }

        $arrPostParams['where'] = "account_id=" . $this->arrUser['account_id'];
        
        // 入库
        $this->load->model('Account');
        $bolRes = $this->Account->updateAccountBaseInfo($arrPostParams);

        if ($bolRes) {
            unset($arrPostParams['where']);
            return $this->outJson($arrPostParams, ErrCode::OK, '账户信息修改成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//

    /**
     * 财务信息认证和重新认证
     */
    public function AuthFinanceInfo() {//{{{//
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN); 
        }

        /* 0为公司 1为个人 */
        //$arrPostParams = $this->input->post();
        $arrPostParams = json_decode(file_get_contents('php://input'), true);
        $arrValidKeys = $arrPostParams['financial_object'] == '1' ? self::VALID_ACCOUNT_COMPANY_FINANCE_KEY : self::VALID_ACCOUNT_PERSIONAL_FINANCE_KEY;
        
        if (empty($arrPostParams) || count($arrPostParams) !== count($arrValidKeys)) {
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
            return $this->outJson('', ErrCode::OK, '财务信息修改成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM, '财务信息修改失败');
     }//}}}//

    /**
     * 上传财务认证图片
     */
    public function UpAuthPhoto(){
        if(empty($this->arrUser)){
            return $this->outJson('',ErrCode::ERR_NOT_LOGIN);
        }
        $this->load->library('UploadTools');
        $arrUdpImgConf = $this->config->item('img');
        $newName = '/authfinance_'.time().mt_rand(100,999).str_replace('image/','.',$_FILES['file']['type']);
        $arrUdpImgConf['file_name'] = $newName; 

        $strUrl = $this->uploadtools->upload($arrUdpImgConf);
        if (empty($strUrl)) {
            return $this->outJson('', ErrCode::ERR_UPLOAD, '上传csv文件失败，请重试');
        }
        return $this->outJson(
            ['url' => $strUrl],
            ErrCode::OK,
            '图片上传成功'
        );
    }

}
