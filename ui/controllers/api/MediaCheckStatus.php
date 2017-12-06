<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 用户上传签名app之后点 
 */

class MediaCheckStatus extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 用户上传签名app之后 或 用户已经在H5站添加了app_key之后，点完成调用
     * check_status 0 => 2
     */
    public function index() {
        $arrPostParams = json_decode(file_get_contents('php://input'), true);
        $arrUpdate = [
            'app_verify_url' => isset($arrPostParams['app_verify_url']) ? $arrPostParams['app_verify_url'] : '',
            'check_status' => 2,
            'where' => "app_id='" . $this->input->post('app_id', true) . "'",
        ];

        $this->load->model('Media');
        $bolRes = $this->media->updateMediaInfo($arrUpdate);
        if (!$bolRes) {
            return $this->outJson('', ErrCode::ERR_SYSTEM, '提交失败');
        }
        return $this->outJson('', ErrCode::ERR_OK);
    }

}
