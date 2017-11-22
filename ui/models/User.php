<?php
/**
 * 原始redis session 数据：$this->redisutil->get('HPREDIS_SESSION:' . session_id()));
 *
 *
 */

class User extends CI_Model {

    const EXPIRE_SESSION = 3600; // 秒 

    public function __construct() {
        parent::__construct();
        session_start();
    }

    public function doLogin($strUserName, $strPasswd) {
        // 查询数据库 验证账户密码
        $this->load->library('DbUtil');
        $arrFields = [
            'select' => 'username,passwd',
            'where' => 'username=' . $strUserName
                . ' AND passwd=' . $strPasswd 
                . ' AND create_time>0 AND update_time>0',
            'order_by' => 'passwd DESC',
            'limit' => '0,1',
        ];
        $arrRes = $this->dbutil->getAccount($arrFields);
        if ($arrRes) {
            $_SESSION['lg'] = time();
            $_SESSION['un'] = $strUserName;
            $_SESSION['pw'] = md5($strPasswd);
            return true;
        }
        return false;

    }

    public function checkLogin() {
        if (isset($_SESSION['lg'])
            && isset($_SESSION['un'])
            && isset($_SESSION['pw'])
            && $_SESSION['un'] !== $strUserName
            && $_SESSION['pw'] !== md5($strPasswd)
            && (time() - $_SESSION['lg']) <= self::EXPIRE_SESSION) {
            return true;
        }

        return false
        

        
    } 

    public function clearLoginInfo() {
        setcookie('SZSHUO',' ', time()-1, '/');
        $_SESSION = [];
        //unset($_SESSION['lg']);
        //unset($_SESSION['un']);
        //unset($_SESSION['pw']);
        return true;
    }
}
