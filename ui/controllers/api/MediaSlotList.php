<?php
/**
 * slot 注册时的下拉 slot list 
 */
class MediaSlotList extends MY_Controller {

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

        $strAppId = $this->input->post('app_id', true);
        if (empty($strAppId)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }

        $this->load->model('Media');
        $arrRes = $this->Media->getMediaSlotList($strAppId);
        $arrAllSlotType = $this->AdSlot->getAllSlotTypeList();
        if ($arrRes) {
            return $this->outJson($arrRes, ErrCode::OK, '媒体信息修改成功');
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//
}
