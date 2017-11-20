<?php
class Ads extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAdsenseLists() {
        $this->load->model('ads/Adsense');
        $arrData = $this->Adsense->getList();
        return $arrData;
    } 
}
