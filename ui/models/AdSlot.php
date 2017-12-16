<?php
class AdSlot extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('DbUtil');
    }

    /**
     * 
     */
    public function getAdSlotList($strAccountId, $pn = 1, $rn = 10, $strSlotName = '') {
        $this->load->library('DbUtil');
        $arrSelect = [
            'select' => 'count(*) as total',
            'where' => "account_id='" . $strAccountId . "'",
            'order_by' => 'slot_style,update_time desc',
        ];
        if (!empty($strSlotName)) {
            $arrSelect['where'] .= " AND slot_name like '%" . $strSlotName . "%'"; 
        }
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
            'where' => "account_id='" . $strAccountId . "'",
            'order_by' => 'create_time DESC',
            'limit' => $rn*($pn-1) . ',' . $rn,
        ];
        if (!empty($strSlotName)) {
            $arrSelect['where'] .= " AND slot_name like '%" . $strSlotName . "%'"; 
        }
        $arrRes = $this->dbutil->getAdSlot($arrSelect);
        if (!empty($arrRes[0])) {
            $this->config->load('style2platform_map');
            $arrStyleMap = $this->config->item('style2platform_map');
            foreach ($arrRes as &$arrSlot) {
                foreach ($arrStyleMap[$arrSlot['slot_style']] as $key => $val) {
                    if ($key === 'des') {
                        continue;
                    }
                    $arrSlot['slot_size'] = $val['size'][$arrSlot['slot_size']];
                    break;
                }
                $arrSlot['slot_style'] = $arrStyleMap[$arrSlot['slot_style']]['des'];
            }
        }

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
            'select' => 'slot_style,img,size',
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
        $arrAppIdMap = $this->InsertAdslot->checkMediaLigal($arrParams);
        if (empty($arrAppIdMap)) {
            return false;
        }

        // 生成本站媒体的slot_id
        $arrParams['slot_id'] = $this->dbutil->getAutoincrementId('adslot');

        // 有多少个slot_style的上游，就从从预生成的slotid中分配几个和本站的slot_id对应，并插如映射记录到映射表
        $arrPreSlotIds = $this->InsertAdslot->getPreSlotid($arrParams['app_id']); 
        if (empty($arrPreSlotIds)) {
            return false;
        }

        $arrSlotIdsForApp = $this->InsertAdslot->distributePreSlotId(
            $arrPreSlotIds,
            intval($arrParams['slot_style']),
            intval($arrParams['slot_size']), 
            $arrParams['app_id'],
            $arrAppIdMap
        );
        if (empty($arrSlotIdsForApp)) {
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
            ErrCode::$msg = '广告位申请失败，请重试或联系工作人员';
            return false;
        }

        // 格式化数据，插入data_for_sdk
        // TODO
        $this->load->model('SyncSdkMediaInfo');
        $this->SyncSdkMediaInfo->syncWhenAdSlotIdRegist($arrParams['app_id'], $arrParams['slot_id'], $arrParams['slot_style'], $arrSlotIdsForApp);

        return true;
    }

}
