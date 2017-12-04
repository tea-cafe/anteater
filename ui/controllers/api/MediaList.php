<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 媒体列表
 */

class MediaList extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

	/**
	 */
	public function index()
	{
        $condition = $this->input->get('media_name', true);
        $checkstatus = $this->input->get('check_status', true);
        $pn = intval($this->input->get('currentPage'));
        $rn = intval($this->input->get('pageSize'));
        $total = intval($this->input->get('total'));
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }
        $this->load->model('Media');
        $arrData = $this->Media->getMediaLists($this->arrUser['account_id'], $pn, $rn, $total, $condition, $checkstatus);
        $this->outJson($arrData, ErrCode::OK);
	}

}
