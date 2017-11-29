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
    public function getAccountInfo() {
        $this->load->model('account/Info');
        $arrData = $this->Lists->getInfo();
        return $arrData;
    } 

    /**
     * 账户基本信息注册
     * @param array $arrParams
     * return array
     */
    public function insertAccountBaseInfo($arrParams) {
        $this->load->library('DbUtil');
        $arrRes = $this->dbutil->setAccount($arrParams);
        return $arrRes;
    }

    /**
     * 账户财务信息提交
     * @param array $arrParams
     * @return array
     */
    public function updateAccountFinanceInfo($arrParams) {
        $this->load->library('DbUtil');
        $arrRes = $this->dbutil->udpAccount($arrParams);
        return $arrRes;
    }
}
