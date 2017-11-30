<?php
/**
 * slot 注册时的下拉选择的slot list 
 */
class AdSlotStyleList extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取媒体注册时当前用户媒体可选的slot类型下拉列表
     */
    public function index() {//{{{//
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }

        $strAppId = $this->input->post('app_id', true);
        $strSlotType = $this->input->post('slot_type');
        if (empty($strAppId)
            || empty($strSlotType)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }

        // TODO 理论上前端有这个数据就不用传，直接前端判断
        $this->load->model('Media');
        $arrMediaValidSlotIds = $this->Media->getMediaValidSlotIds($strAppId);
        $arrMediaValidSlotIds = explode(',', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15');

        $this->load->model('AdSlot');
        $arrAllSlotType = $this->AdSlot->getAllSlotTypeList($strSlotType);

        $arrSlotCanBeChoice = [];
        if (!empty($arrAllSlotType)) {
            foreach ($arrAllSlotType as $arrSlotInfo) {
                if (in_array($arrSlotInfo['slot_style_id'], $arrMediaValidSlotIds)) {
                    $arrSlotCanBeChoice[] = $arrSlotInfo;
                }
            }
        }
        if (!empty($arrSlotCanBeChoice)) {
            return $this->outJson($arrSlotCanBeChoice, ErrCode::OK);
        }
        return $this->outJson('', ErrCode::ERR_SYSTEM);
    }//}}}//
}
