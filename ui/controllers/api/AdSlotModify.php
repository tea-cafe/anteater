<?php
/**
 * slot 修改操作 
 */
class AdSlotModify extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 删除广告位
     */
    public function delSlot() {
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }
        $arrPostParams = json_decode(file_get_contents('php://input'), true);
        $strAppId = $arrPostParams['app_id'];
        $strSlotId = $arrPostParams['slot_id'];

        if (empty($strAppId)
            || empty($strSlotId)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        $this->load->model('AdSlot');
        $code = $this->AdSlot->delSlot($this->arrUser['account_id'], $strAppId, $strSlotId);
        if ($code == 0) {
            return $this->outJson('删除成功', ErrCode::OK);
        }
        if ($code == -1) {
            return $this->outJson('请确认广告位属于此媒体', ErrCode::ERR_SYSTEM);
        }

        return $this->outJson('', ErrCode::ERR_SYSTEM);

    }

    /**
     * 名称修改 
     */
    public function changeName() {//{{{//
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }

        $arrPostParams = json_decode(file_get_contents('php://input'), true);
        $strAppId = $arrPostParams['app_id'];
        $strSlotId = $arrPostParams['slot_id'];
        $strNewName = $arrPostParams['slot_name'];

        if (empty($strAppId)
            || empty($strSlotId)
            || empty($strNewName)
            || preg_match('#[^0-9_a-zA-Z\x{4e00}-\x{9fa5}-]#u', $strNewName)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        $this->load->model('AdSlot');
        $code = $this->AdSlot->modifySlotName($this->arrUser['account_id'], $strAppId, $strSlotId, $strNewName);
        if ($code == 0) {
            return $this->outJson('修改成功', ErrCode::OK);
        }
        if ($code == -1) {
            return $this->outJson('请确认app_id属于此账号', ErrCode::ERR_SYSTEM);
        }

        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//
}
