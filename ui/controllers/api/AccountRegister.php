<?php
/**
 * 用户注册接口
 * szishuo
 */
class AccountRegister extends MY_Controller {

    const WHITE_PARAMS_KEY = [
        'email', 
        'passwd',
        'phone', 
        'company',
        'contact_person',
    ]; 
        
    public function __construct() {
        parent::__construct();
    }

    /**
     *
     */
    public function index() {
        $arrPostParams = $this->input->post();
        if (empty($arrPostParams)
            || count($arrPostParams) !== count(self::WHITE_PARAMS_KEY)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        foreach ($arrPostParams as $key => &$val) {
            if(!in_array($key, self::WHITE_PARAMS_KEY)) {
                return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
            }
            $val = $this->security->xss_clean($val);
        }
        $arrPostParams['passwd'] = md5($arrPostParams['passwd']);

        // 转移到model层

        $this->load->library('DbUtil');
        $arrRes = $this->dbutil->setAccount($arrPostParams);
        if ($arrRes['code'] === 0) {
            return $this->outJson('', ErrCode::OK, '注册成功');
        }
        if ($arrRes['code'] === 1062) {
            return $this->outJson('', ErrCode::ERR_DUPLICATE_ACCOUNT, $arrRes['message'] . '已经被注册');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM, '注册失败');
    }
}
