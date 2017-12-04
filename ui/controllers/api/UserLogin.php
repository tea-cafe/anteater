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
        $arrPostParams = json_decode(file_get_contents('php://input'), true);
        $strUserName = $arrPostParams['username'];
        $strPasswd = $arrPostParams['passwd'];
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
