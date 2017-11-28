<?php
class PreAdSlotManager extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insertPreAdSlot($sql) {

        $this->load->database();
        $arrRes = $this->db->query($sql);
        var_dump($arrRes);exit;

    }

}
