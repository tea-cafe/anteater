<?php
/**
 * 账户相关 总类
 */
class Account extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取媒体信息
     */
    public function getAccountInfo($intAccountId) {
        $this->load->model('account/Info');
        $arrData = $this->Info->getInfo($intAccountId);
        return $arrData;
    } 

    /**
     * 账户基本信息注册
     * @param array $arrParams
     * @return array
     */
    public function insertAccountBaseInfo($arrParams) {
        $this->load->library('DbUtil');
        $arrRes = $this->dbutil->setAccount($arrParams);
        return $arrRes;
    }

    /**
     * 用户基本信息修改
     * @param array
     * @return bool
     */
    public function updateAccountBaseInfo($arrParams) {
        $this->load->library('DbUtil');
        $bolRes = $this->dbutil->udpAccount($arrParams);
        return $bolRes;
    }

    /**
     * 账户财务信息提交
     * @param array $arrParams
     * @return bool
     */
    public function updateAccountFinanceInfo($arrParams) {
        $this->load->library('DbUtil');
        $bolRes = $this->dbutil->udpAccount($arrParams);
        return $bolRes;
    }
}
