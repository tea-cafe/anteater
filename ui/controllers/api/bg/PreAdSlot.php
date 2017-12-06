<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 后台 预生成slot_id管理 控制器 
 */

class PreAdSlot extends MY_Controller {


	/**
     *
	 */
	public function index() {
        $arrPostData = json_decode(file_get_contents('php://input'), true);
        var_dump($arrPostData);exit;
          
    }
}
