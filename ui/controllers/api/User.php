<?php
/**
 * 用户登录接口
 * szishuo
 */
class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     *
     */
    public function index() {
        $strUserName = $this->input->post('username');
        $strPasswd = $this->input->post('passwd');
        $bolRes = $this->User->doLogin($strUserName, $strPasswd);
        if ($bolRes) {
            return $this->outJson('', ErrCode::OK, '登录成功');
        }
        return $this->outJson('', ErrCode::ERR_LOGIN_FAILED);
    }
}
