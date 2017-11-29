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
        $this->load->model('User');
        $arrUser = $this->User->checkLogin();
        if (empty($arrUser)) {
            $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }
        $this->load->model('Media');
        $arrData = $this->Media->getMediaLists($arrUser['account_id']);
        $this->outJson($arrData, ErrCode::OK);
	}
}
