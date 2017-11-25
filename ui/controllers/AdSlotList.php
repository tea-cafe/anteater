<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 广告位列表
 */

class AdSlotList extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

	/**
	 */
	public function index()
	{
        $this->load->model('AdSlot');
        $arrData = $this->AdSlot->getAdsenseLists();
        $this->outJson($arrData);
	}
}
