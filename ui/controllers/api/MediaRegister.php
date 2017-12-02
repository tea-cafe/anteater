<?php
/**
 * media注册接口
 * szishuo
 */
class MediaRegister extends MY_Controller {

    const VALID_MEDIA_KEY = [
        'media_name',
        'media_platform',
        'app_package_name',
        'media_keywords',
        'media_desc',
        'app_download_url',
        'url',
    ];

    public function __construct() {
        parent::__construct();
        $this->load->model('User');
        $this->arrUser = $this->User->checkLogin();
    }

    /**
     * 基本信息注册
     */
    public function index() {//{{{//
        //if (empty($this->arrUser)) {
        //    return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        //}

        $arrPostParams = json_decode($GLOBALS['HTTP_RAW_POST_DATA'], true);
        if (empty($arrPostParams['media_platform'])) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }

        // TODO 各种号码格式校验
        foreach ($arrPostParams as $key => &$val) {
            if(!in_array($key, self::VALID_MEDIA_KEY)) {
                return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
            }
            $val = $this->security->xss_clean($val);
        }
        //$arrPostParams['account_id'] = $this->arrUser['account_id'];
        $arrPostParams['account_id'] = 1;
        $this->load->model('Media');
        $bolRes = $this->Media->insertMediaInfo($arrPostParams);
        if ($bolRes) {
            return $this->outJson('', ErrCode::OK, '媒体注册成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//

}
