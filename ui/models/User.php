<?php
/**
 * 原始redis session 数据：$this->redisutil->get('HPREDIS_SESSION:' . session_id()));
 *
 * $user = [
 *     'pk' => primary_key(id), 用于所有的登陆后查询
 *     'username' => 'xxx',
 * ]
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
            'select' => 'id,email,passwd,',
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
        $_SESSION['pk'] = $arrRes[0]['id'];
        $_SESSION['un'] = $arrRes[0]['email'];
        $_SESSION['pw'] = $arrRes[0]['passwd'];
        return true;
    }

	/**
     * @return array
	 */
    public function checkLogin() {
        if (isset($_SESSION['lg'])
            && isset($_SESSION['pk'])
            && isset($_SESSION['un'])
            && isset($_SESSION['pw'])
            && (time() - $_SESSION['lg']) <= self::EXPIRE_SESSION) {
            return [
                'pk' => $_SESSION['pk'],
                'username' => $_SESSION['un'],
            ];
        }
        return [];
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
