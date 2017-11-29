<?php
/**
 * 媒体列表
 *
 */
class Lists extends CI_Model {
    

    public function __construct() {
        parent::__construct();

    }

    /**
     * 获取媒体信息
     *
     * @return array
     */
    public function getLists($intAccountId) {
        $this->load->library('DbUtil');
        $arrSelect = [
            'select' => 'media_name,app_id,create_time,media_platform,check_status',
            'where' => "account_id=" . $intAccountId,
        ];
        $arrRes = $this->dbutil->getMedia($arrSelect);
        return $arrRes;
    } 
}
