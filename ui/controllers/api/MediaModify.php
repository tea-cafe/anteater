<?php
/**
 * media注册接口
 * szishuo
 */
class MediaModify extends MY_Controller {

    const VALID_MEDIA_KEY = [
        'media_name',
        'media_platform',
        'app_detail_url',
        'app_package_name',
        'media_keywords',
        'media_desc',
        'url',
        'app_platform',
        'industry',
        'media_delivery_method',
    ];

    public function __construct() {
        parent::__construct();
    }

    /**
     * 媒体信息修改
     */
    public function index() {//{{{//
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
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
        $arrPostParams['where'] = "app_id='" . $arrPostParams['app_id'] . "'";
        unset($arrPostParams['app_id']);
        $this->load->model('Media');
        $bolRes = $this->Media->updateMediaInfo($arrPostParams);
        if ($bolRes) {
            return $this->outJson('', ErrCode::OK, '媒体信息修改成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//

}
