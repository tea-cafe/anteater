<?php
class Media extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getMediaLists() {
        $this->load->model('media/Lists');
        $arrData = $this->Lists->getLists();
        return $arrData;
    } 
}
