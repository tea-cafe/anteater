<?php
/**
 * 查询对应上游的slot_id
 */
class AdSlotUpid extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 获取媒体信息
     */
    public function index() {
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }

        $slot_id = $this->input->get('slot_id', true);
        $this->load->model('AdSlot');
        $up_slot_id = $this->AdSlot->getUpstreamSlotId($this->arrUser['account_id'],$slot_id);
        return $this->outJson(['upsid' => $up_slot_id], ErrCode::OK, '');
    }
}

