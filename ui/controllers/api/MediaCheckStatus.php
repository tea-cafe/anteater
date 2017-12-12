<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 用户上传签名app之后点 
 */

class MediaCheckStatus extends MY_Controller {

    const VALID_PARAMS_KEY = [
        'app_id',
        'app_verify_url', 
    ];

    public function __construct() {
        parent::__construct();
    }

    /**
     * 用户上传签名app之后 或 用户已经在H5站添加了app_key之后，点完成调用
     * check_status 0 => 2
     */
    public function index() {
        if(empty($this->arrUser)){
            return $this->outJson('',ErrCode::ERR_NOT_LOGIN);
        }
        $arrPostParams = json_decode(file_get_contents('php://input'), true);
        foreach ($arrPostParams as $key => &$val) {
            if(!in_array($key, self::VALID_PARAMS_KEY)) {
                return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
            }
            $val = $this->security->xss_clean($val);
        }
        if (empty($arrPostParams['app_id'])) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        $arrUpdate = [
            'check_status' => 2,
            'app_verify_url' => $arrPostParams['app_verify_url'],
            'where' => "app_id='" . $arrPostParams['app_id'] . "'",
        ];

        $this->load->model('Media');
        $bolRes = $this->Media->updateMediaInfo($arrUpdate);
        if (!$bolRes) {
            return $this->outJson('', ErrCode::ERR_SYSTEM, '提交失败');
        }
        return $this->outJson('', ErrCode::OK);
    }

}
