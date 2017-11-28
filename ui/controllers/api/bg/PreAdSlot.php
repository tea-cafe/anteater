<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 后台 预生成slot_id管理 控制器 
 */

class PreAdSlot extends MY_Controller {

    const VALID_ADSLOT_KEY = [
        'app_id',
        'data',
    ];

    public function __construct() {
        parent::__construct();
    }

	/**
     *
	 */
	public function testInsertPreAdSlot() {
        $arrPostParams = $this->input->post();
        
        foreach($arrPostParams as $key => $val) {

        }

        include_once('/home/work/test.php');
        $jsonData = json_encode($data);
        echo $jsonData;

        $sql = 'insert into pre_adslot(app_id,data) values(' . $app_id . "','" . $jsonData . "\'";
        $sql = substr($sql, 0, -1);
        $this->load->model('bg/AdSlotManager');
        $this->AdSlotManager->insertAdSlotStyle($sql);

          
    }
}
