<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 接口 广告位注册
 */

class AdSlotRegister extends MY_Controller {

    const VALID_ADSLOT_KEY = [
        'slot_name',
        'app_id',
        'slot_style',
        'slot_size',
    ];

    public function __construct() {
        parent::__construct();
    }

	/**
     *
	 */
	public function index() {
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }
        $arrPostParams = json_decode(file_get_contents('php://input'), true); 

        $app_id = $this->security->xss_clean($arrPostParams['app_id']);
        $slot_name = $this->security->xss_clean($arrPostParams['slot_name']);
        $slot_style = intval($arrPostParams['slot_type'][0]); 
        $slot_size = intval($arrPostParams['slot_type'][1]);

        $arrParams = compact('app_id', 'slot_name', 'slot_style', 'slot_size');

        $arrParams['account_id'] = $this->arrUser['account_id'];

        $this->load->model('AdSlot');
        $bolRes = $this->AdSlot->addAdSlotInfo($arrParams);
        if ($bolRes) {
            return $this->outJson('', ErrCode::OK, '注册成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
	}
}
