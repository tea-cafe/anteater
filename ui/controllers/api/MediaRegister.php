<?php
/**
 * media注册接口
 * szishuo
 */
class MediaRegister extends MY_Controller {

    const VALID_APP_MEDIA_KEY = [
        'media_name',
        'media_platform',
        'app_package_name',
        'media_keywords',
        'media_desc',
        'app_download_url',
    ];

    const VALID_H5_MEDIA_KEY = [
        'media_name',
        'media_platform',
        'url',
        'media_keywords',
        'media_desc',
    ];

    const VALID_PS_MEDIA_KEY = [
        'media_name',
        'media_platform',
        'url',
        'public_sign_name',
        'public_sign_type',
        'public_sign_object',
        'media_keywords',
        'media_desc',
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
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }

        $arrPostParams = $this->input->post();
        if (empty($arrPostParams['media_platform'])) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        switch($arrPostParams['media_platform']) {
            case 'h5':
                $strValidKeys = self::VALID_H5_MEDIA_KEY;
                break;
            case 'public_sign':
                $strValidKeys = self::VALID_PS_MEDIA_KEY; 
                break;
            default:
                $strValidKeys = self::VALID_APP_MEDIA_KEY;
        }

        if (empty($arrPostParams)
            || count($arrPostParams) !== count($strValidKeys)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }

        // TODO 各种号码格式校验
        foreach ($arrPostParams as $key => &$val) {
            if(!in_array($key, $strValidKeys)) {
                return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
            }
            $val = $this->security->xss_clean($val);
        }
        $arrPostParams['account_id'] = $this->arrUser['account_id'];
        $this->load->model('Media');
        $bolRes = $this->Media->insertMediaInfo($arrPostParams);
        if ($bolRes) {
            return $this->outJson('', ErrCode::OK, '媒体注册成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//

}
