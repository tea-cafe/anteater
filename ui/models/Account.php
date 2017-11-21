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
    public function getMediaLists() {
        $this->load->model('account/Info');
        $arrData = $this->Lists->getInfo();
        return $arrData;
    } 
}
