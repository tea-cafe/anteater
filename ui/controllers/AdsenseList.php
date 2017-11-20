<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 广告位列表
 */

class AdsenseList extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

	/**
	 */
	public function index()
	{
        $this->load->model('Ads');
        $arrData = $this->Ads->getAdsenseLists();
        $this->outJson($arrData);
	}
}
