<?php
/**
 * 用户注册
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

        $this->load->library('DbUtil');
        //$this->dbutil->getMedia($arrPostParams);
        $bolRes = $this->dbutil->setAccount($arrPostParams);
        if ($bolRes) {
            return $this->outJson('', ErrCode::OK, '注册成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }
}
