<?php
/**
 * media注册接口
 * szishuo
 */
class MediaRegister extends MY_Controller {

    const VALID_APP_MEDIA_KEY = [
        'media_name',
        'platform',
        'app_package_name',
        'media_keywords',
        'media_desc',
        'app_download_url',
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
        if (empty($arrPostParams)
            || count($arrPostParams) !== count(self::VALID_APP_MEDIA_KEY)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        // TODO 各种号码格式校验
        foreach ($arrPostParams as $key => &$val) {
            if(!in_array($key, self::VALID_APP_MEDIA_KEY)) {
                return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
            }
            $val = $this->security->xss_clean($val);
        }
        $arrPostParams['email'] = $this->arrUser['email'];
        $arrPostParams['app_id'] = mysql_insert_id() + 1000;
        echo json_encode($arrPostParams);exit;
        $this->load->model('Media');
        $arrRes = $this->Media->insertMediaInfo($arrPostParams);

        if ($arrRes['code'] === 0) {
            return $this->outJson('', ErrCode::OK, '媒体注册成功');
        }
        if ($arrRes['code'] === 1062) {
            return $this->outJson('', ErrCode::ERR_DUPLICATE_ACCOUNT);
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//

}
