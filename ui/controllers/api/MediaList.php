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
        $condition = $this->input->get('media_name');
        $pn = intval($this->input->get('pn'));
        $rn = intval($this->input->get('rn'));
        $total = intval($this->input->get('total'));
        //if (empty($arrUser)) {
        //    $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        //}
        $this->arrUser['account_id'] = 1;
        $this->load->model('Media');
        $arrData = $this->Media->getMediaLists($this->arrUser['account_id'], $pn, $rn, $total, $condition);

        $this->outJson($arrData, ErrCode::OK);
	}

}
