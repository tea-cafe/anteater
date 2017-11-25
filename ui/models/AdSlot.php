<?php
class AdSlot extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insertAdSlotInfo($arrParams) {
        $this->load->library('DbUtil');

        $arrCheckMediaLegal = [
            'select' => 'app_id',
            'where' => 'app_id=' . $arrParams['app_id'] .' AND media_name=' . $arrParams['media_name'] . ' AND check_status=1',
            'limit' => '1',
        ];
        var_dump($arrCheckMediaLegal);exit;

        $arrCheckRes = $this->dbutil->getMedia($arrParams);
        var_dump($arrCheckRes);exit;
        $this->dbutil->setAdSlot($arrParams);
        return $arrData;
    } 

    /**
     *
     */
    private function addExtraInfo() {
        // 
    }
}
