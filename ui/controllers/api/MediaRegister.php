<?php
/**
 * media注册接口
 * szishuo
 */
class MediaRegister extends MY_Controller {

    const VALID_MEDIA_KEY = [
        'media_name',
        'media_platform',
        'app_download_url',
        'app_package_name',
        'media_keywords',
        'media_desc',
        'url',
    ];

    const VALID_MEDIA_VARIFY_KEY = [
        'media_id',
        'media_platform',
        'app_package_name',
        'app_download_url',
        'h5_app_key',    
    ];

    public function __construct() {
        parent::__construct();
    }

    /**
     * 基本信息注册
     */
    public function index() {//{{{//
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }

        $arrPostParams = json_decode(file_get_contents('php://input'), true);
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
        $arrPostParams['account_id'] = $this->arrUser['account_id'];
        $this->load->model('Media');
        $bolRes = $this->Media->insertMediaInfo($arrPostParams);
        if ($bolRes) {
            return $this->outJson('', ErrCode::OK, '媒体注册成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//

    /**
     * 上传包，或者appkey 之后，置状态为1 待验证
     */
    public function verifyStatus() {
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }

        $arrPostParams = json_decode(file_get_contents('php://input'), true);
        if (empty($arrPostParams['media_platform'])) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }

        // TODO 各种号码格式校验
        foreach ($arrPostParams as $key => &$val) {
            if(!in_array($key, self::VALID_MEDIA_VARIFY_KEY)) {
                return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
            }
            $val = $this->security->xss_clean($val);
        }

        unset($arrPostParams['media_id']);
        $this->load->model('Media');

        $arrUpdate['where'] = 'account_id=' . $this->arrUser['account_id'] . " AND media_id='" . $arrPostParams['media_id'] . "'";
        $bolRes = $this->Media->updateMediaInfo($arrPostParams);
        if ($bolRes) {
            return $this->outJson('', ErrCode::OK, '提交验证信息成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//

}
