<?php
/**
 * 用户登录接口
 * szishuo
 */
class UserLogin extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     */
    public function index() {
        $strUserName = $this->input->post('username', true);
        $strPasswd = $this->input->post('passwd', true);
        if (empty($strUserName)
            || empty($strPasswd)) {
            return $this->outJson('', ErrCode::ERR_LOGIN_FAILED);
        }
        $this->load->model('User');
        $bolRes = $this->User->doLogin($strUserName, $strPasswd);
        if ($bolRes) {
            return $this->outJson('', ErrCode::OK, '登录成功');
        }
        return $this->outJson('', ErrCode::ERR_LOGIN_FAILED);
    }
}
