<?php
/**
 * 原始redis session 数据：$this->redisutil->get('HPREDIS_SESSION:' . session_id()));
 *
 */

class User extends CI_Model {

    const EXPIRE_SESSION = 3600; // 秒 

	/**
     *
	 */
    public function __construct() {
        parent::__construct();
        session_start();
    }

	/**
     * @param string $strUserName
     * @param string $strPasswd
     * @return bool
	 */
    public function doLogin($strUserName, $strPasswd) {
        // 查询数据库 验证账户密码
        $this->load->library('DbUtil');
        $arrFields = [
            'select' => 'email,passwd',
            'where' => 'email=\'' . $strUserName . '\''
                . ' AND passwd=\'' . md5($strPasswd) . '\'' 
                . ' AND create_time>0 AND update_time>0',
            'order_by' => 'passwd DESC',
            'limit' => '0,1',
        ];
        $arrRes = $this->dbutil->getAccount($arrFields);
        if (empty($arrRes)) {
            return false;
        }
        $_SESSION['lg'] = time();
        $_SESSION['un'] = $strUserName;
        $_SESSION['pw'] = md5($strPasswd);
        return true;
    }

	/**
     *
	 */
    public function checkLogin() {
        if (isset($_SESSION['lg'])
            && isset($_SESSION['un'])
            && isset($_SESSION['pw'])
            && (time() - $_SESSION['lg']) <= self::EXPIRE_SESSION) {
            return true;
        }
        return false;
    } 

	/**
     *
	 */
    public function clearLoginInfo() {
        setcookie('SZSHUO', '', time()-1, '/');
        $_SESSION = [];
        return true;
    }
}
