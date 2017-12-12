<?php
class AdSlot extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('DbUtil');
    }

    /**
     * 
     */
    public function getAdSlotList($intAccountId, $pn = 1, $rn = 10, $strSlotName = '') {
        $this->load->library('DbUtil');
        $arrSelect = [
            'select' => 'count(*) as total',
            'where' => 'account_id=' . $intAccountId,
        ];
        $arrRes = $this->dbutil->getAdSlot($arrSelect);
        if (empty($arrRes)) {
            $intCount = 0;
            $arrRes = [];
            return [
                'list' => $arrRes,
                'pagination' => [
                    'total' => $intCount,
                    'pageSize' => $rn,
                    'current' => $pn,
                ],
            ];
        }
        $intCount = $arrRes[0]['total'];
        $arrSelect = [
            'select' => 'slot_id,app_id,media_name,media_platform,slot_name,slot_style,slot_size,switch,create_time',
            'where' => 'account_id=' . $intAccountId,
            'order_by' => 'create_time DESC',
            'limit' => $rn*($pn-1) . ',' . $rn,
        ];
        if (!empty($condition)) {
            $arrSelect['where'] .= " AND media_name like '%" . $strSlotName . "%'"; 
        }
        $arrRes = $this->dbutil->getAdSlot($arrSelect);
        return [
            'list' => $arrRes,
            'pagination' => [
                'total' => $intCount,
                'pageSize' => $rn,
                'current' => $pn,
            ],
        ];
    }

    /**
     * @param $strSlotType
     * @return array
     */
    public function getAllSlotTypeList($strSlotType) {
        $arrSelect = [
            'select' => 'slot_style_id,slot_style,img,size',
            'where' => "slot_frozen_status=0 AND slot_type='" . $strSlotType . "'",
        ];
        $arrRes = $this->dbutil->getAdslotstyle($arrSelect);  
        return $arrRes;
    }

    /**
     * @param array $arrParams
     * @return array
     */
    public function addAdSlotInfo($arrParams) {
        $this->load->model('adslot/InsertAdslot');
        // 检验媒体是否过审
        $bolRes = $this->InsertAdslot->checkMediaLigal($arrParams);
        if (!$bolRes) {
            // todo
            echo 'checkMediaLigal false';exit; 
            return false;
        }

        // 生成本站媒体的slot_id
        $arrParams['slot_id'] = $this->dbutil->getAutoincrementId('adslot');

        // 有多少个slot_style的上游，就从从预生成的slotid中分配几个和本站的slot_id对应，并插如映射记录到映射表
        $arrPreSlotIds = $this->InsertAdslot->getPreSlotid($arrParams['app_id']); 
        if (empty($arrPreSlotIds)) {
            echo 'pre slot id 为空';exit;
            ErrCode::$msg = '广告位申请超出限制，请联系工作人员';
            return false;
        }

        $arrSlotIdsForApp = $this->InsertAdslot->distributePreSlotId(
            $arrPreSlotIds,
            intval($arrParams['slot_style']),
            intval($arrParams['slot_size']), 
            $arrParams['app_id']
        );
        if (empty($arrSlotIdsForApp)) {
            ErrCode::$msg = '此类型广告位申请超额，请联系工作人员';
            return false; 
        }
        
        // 更新slot_id map
        $bolRes = $this->InsertAdslot->insertSlotMap(
            $arrSlotIdsForApp, 
            $arrParams['account_id'], 
            $arrParams['slot_id'],
            $arrParams['app_id']
        );
        if (!$bolRes) {
            return false;
        }

        $arrParams['upstream_adslots']= json_encode($arrSlotIdsForApp);
        // 插入adslot_info
        $arrRes = $this->dbutil->setAdslot($arrParams);
        if (!$arrRes
            || $arrRes['code'] !== 0) {
            return false;
        }

        // 格式化数据，插入data_for_sdk
        $this->load->model('syncSdkMediaInfo');
        $this->syncSdkMediaInfo->syncWhenAdSlotIdRegist($arrParams['app_id'], $arrParams['slot_id'], $arrParams['slot_style'], $arrSlotIdsForApp);

        return true;
    }

}
