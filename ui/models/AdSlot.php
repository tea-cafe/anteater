<?php
class AdSlot extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('DbUtil');
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
    public function insertAdSlotInfo($arrParams) {
        // 检验媒体是否过审
        $arrCheckMediaLegal = [
            'select' => 'app_id',
            'where' => "app_id='" . $arrParams['app_id'] . "' AND media_name='" . $arrParams['media_name'] . "' AND check_status=2",
            'limit' => '0,1',
        ];
        $arrRes = $this->dbutil->getMedia($arrCheckMediaLegal);
        if (empty($arrRes[0])) {
            return false;
        }

        // 生成媒体的slot_id
        $arrParams['slot_id'] = $this->dbutil->getAutoincrementId('adslot');

        // 从预生成的slotid中为此slotid 分配， 并插如映射记录到映射表
        $bolRes = $this->getSlotiAndUpdateSlotMap($arrParams['app_id'], $arrParams['slot_type'], $arrParams['slot_style'], $arrParams['slot_size'], $arrParams['slot_id']);
        if (!$bolRes) {
            return false;
        }
        $bolRes = $this->dbutil->setAdslot($arrParams);
        if (!$bolRes) {
            return false;
        }
        return true;
    }

    /**
     * 有个隐含bug，一旦预分配id全部置1，会报系统错误
     *
     */
    private function getSlotiAndUpdateSlotMap($strAppId, $strSlotType, $strSlotStyle, $strSlotSize, $strSlotId) {
        $arrSelect = [
            'select' => 'data',
            'where' => "app_id='" . $strAppId . "'",
        ];
        $arrRes = $this->dbutil->getPreadslot($arrSelect);
        if (empty($arrRes[0]['data'])) {
            return false;
        }
        $arrPreSlotIds = json_decode($arrRes[0]['data'], true);
        //echo json_encode($arrPreSlotIds);exit;
        $arrSlotIdsForApp = [];
        foreach($arrPreSlotIds as $upstream => &$arrType){
            $arrTmp = $arrType[$strSlotType][$strSlotStyle][$strSlotSize];
            foreach ($arrTmp as $slotid => $used) {
                // todo test
                //$arrTmp[$slotid] = 0;continue;
                if ($used === 0) {
                    $arrSlotIdsForApp[] = [
                        'upstream' => $upstream,
                        'upstream_slot_id' => $slotid,
                    ];
                    $arrTmp[$slotid] = 1;
                    break;
                }
            }
            $arrType[$strSlotType][$strSlotStyle][$strSlotSize] = $arrTmp;
        }
        $arrUpdate = [
            'data' => json_encode($arrPreSlotIds),
            'where' => "app_id='" . $strAppId . "'",
        ];
        $arrRes = $this->dbutil->udpPreadslot($arrUpdate);
        if (!$arrRes
            || $arrRes['code'] !== 0) {
            return false;
        }
        
        $sql = 'INSERT INTO adslot_map(slot_id,app_id,upstream,upstream_slot_id,create_time,update_time) VALUES';
        foreach($arrSlotIdsForApp as $val) {
            $sql .= "(" . $strSlotId . ",'" . $strAppId . "','" . $val['upstream'] . "','" . $val['upstream_slot_id'] . "'," . time() . "," . time() . "),";
        }
        $sql = substr($sql, 0, -1);
        $bolRes = $this->dbutil->query($sql);
        return $bolRes;
    }
}
