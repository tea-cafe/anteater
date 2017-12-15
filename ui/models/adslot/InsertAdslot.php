<?php
class InsertAdslot extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('DbUtil');
    }
    
    /**
     * step 1: check Media 信息是否已过审
     */
    public function checkMediaLigal($arrParams) {
        $arrCheckMediaLegal = [
            'select' => 'app_id,app_id_map',
            'where' => "app_id='" . $arrParams['app_id'] . "' AND check_status=3",
            'limit' => '0,1',
        ];
        $arrRes = $this->dbutil->getMedia($arrCheckMediaLegal);
        if (empty($arrRes[0])
            || empty($arrRes[0]['app_id_map'])) {
            ErrCode::$msg = '媒体未过审，请联系工作人员';
            return [];
        }
        $arrAppIdMap = json_decode($arrRes[0]['app_id_map'], true);
        if (empty($arrAppIdMap)) {
            ErrCode::$msg = '媒体映射信息有误，请联系工作人员';
            return [];
        }
        return $arrAppIdMap;
    }


    /**
     * step 2: 获取运营为此media预分配的slot_id列表，
     *
     */
    public function getPreSlotId($strAppId) {
        $arrSelect = [
            'select' => 'data',
            'where' => "app_id='" . $strAppId . "'",
        ];
        $arrRes = $this->dbutil->getPreadslot($arrSelect);
        if (empty($arrRes[0]['data'])) {
            ErrCode::$msg = '广告位申请已超限，请联系工作人员';
            return false;
        }
        return json_decode($arrRes[0]['data'], true);
    }


    /**
     * step 3: 为此媒体分配预生成的广告位id.从与分配的slot_id列表按渠道分别选出一个可用的上游slot_id，标记已占用，然后回写预分配的slot_id列表。最终返回此mediaslot_id对应的每个上游的slot_id列表.
     * @param array $arrPreSlotIds 预分配slot_id数据
     * @param string $intSlotStyle
     * @param string $intSlotSize
     * @param string $strAppId
     * @param array $arrAppIdMap check分配的slotid是否跟上游对的上
     * @return array 分配的上游slot_id列表
     */
    public function distributePreSlotId($arrPreSlotIds, $intSlotStyle, $intSlotSize, $strAppId, $arrAppIdMap) {
        $arrSlotIdsForApp = [];
        foreach($arrPreSlotIds as $upstream => &$arrType){
            if (!array_key_exists($upstream, $arrAppIdMap)) {
                continue; 
            }
            if (empty($arrType)
                || empty($arrType[$intSlotStyle])
                || empty($arrType[$intSlotStyle][$intSlotSize])) {
                continue;
            }
            $arrTmp = $arrType[$intSlotStyle][$intSlotSize];
            foreach ($arrTmp as $slotid => $used) {
                if ($used === 0) {
                    $arrSlotIdsForApp[] = [
                        'upstream' => $upstream,
                        'upstream_slot_id' => $slotid,
                    ];
                    $arrTmp[$slotid] = 1;
                    break;
                }
            }
            $arrType[$intSlotStyle][$intSlotSize] = $arrTmp;
        }

        if (empty($arrSlotIdsForApp)) {
            // pre_slotid 均已使用
            ErrCode::$msg = '广告位申请已超限，请联系工作人员';
            return [];
        }

        // 回写 pre_slotid
        $arrUpdate = [
            'data' => json_encode($arrPreSlotIds, JSON_UNESCAPED_UNICODE),
            'where' => "app_id='" . $strAppId . "'",
        ];
        $arrRes = $this->dbutil->udpPreadslot($arrUpdate);
        if (!$arrRes
            || $arrRes['code'] !== 0) {
            // slot_id 分配后回写失败
            ErrCode::$msg = '广告位申请已超限，请联系工作人员';
            return [];
        }
        return $arrSlotIdsForApp;
    }

    /**
     * step 4: 插入 slot_map记录
     *
     */
    public function insertSlotMap($arrSlotIdsForApp, $strAccountId, $intSlotId, $strAppId) { 
        $sql = 'INSERT INTO adslot_map(account_id,slot_id,app_id,ad_upstream,upstream_slot_id,create_time,update_time) VALUES';
        foreach($arrSlotIdsForApp as $val) {
            $sql .= "('" . $strAccountId . "'" 
                . "," . $intSlotId 
                . ",'" . $strAppId 
                . "','" . $val['upstream'] 
                . "','" . $val['upstream_slot_id'] 
                . "'," . time() 
                . "," . time() . "),";
        }
        $sql = substr($sql, 0, -1);
        $bolRes = $this->dbutil->query($sql);
        if (!$bolRes) {
            // slot_id 映射表更新失败
            ErrCode::$msg = '广告位申请已超限，请联系工作人员';
        }
        return $bolRes;
    }


}
