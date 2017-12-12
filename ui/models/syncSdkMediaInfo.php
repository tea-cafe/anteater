<?php
// 后台触发： 策略更新(更新所有记录)、上游增加(更新一条记录)
// 前台触发： 广告位申请(更新一条记录)

class syncSdkMediaInfo extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('DbUtil');
    }
    

    public function syncWhenAdSlotIdRegist($app_id, $slot_id, $slot_style, $arrUpstreamSlotIdsForApp) {
        if (empty($app_id)
            || empty($slot_style)
            || empty($arrUpstreamSlotIdsForApp)) {
            log_message('error', 'sync data_for_sdk: syncWhenAdSlotIdRegist params error');
            return [];
        }
        // 1 读表 adslot_style_info的display_strategy字段 查策略
        $arrSelect = [
            'select' => 'display_strategy',
            'where' => "slot_style=" . $arrParams['slot_style'],
        ];
        $arrRes = $this->dbutil->getAdslotstyle($arrSelect);
        if (empty($arrRes[0])
            || empty($arrRes[0]['display_strategy'])) {
            return [];
        }
        $arrSlotStrategy = json_decode($arrRes[0]['display_strategy'], true);

        // 2 读表media_info的app_id_map字段，获取app_id s上下游对应关系
        $arrSelect = [
            'select' => 'app_id_map',
            'where' => "app_id='" . $app_id . "'",
        ];
        $arrRes = $this->dbutil->setMedia($arrSelect);
        if (empty($arrRes[0])
            || empty($arrRes[0]['app_id_map'])) {
            return [];
        }
        $arrAppIdMap = json_decode($arrRes[0]['app_id_map'], true);

        // $arrUpstreamSlotIdsForApp 结构参见 model/adslot/InsertAdslot.php distributePreSlotId 方法 
        $arrTmp = [];
        foreach ($arrUpstreamSlotIdsForApp as $arrUpstreamSlotid) {
            if (in_array($arrUpstreamSlotid['upstream'], array_keys($arrSlotStrategy))) {
                $arrTmp['strategy'][$arrUpstreamSlotid['upstream']] = $arrSlotStrategy[$arrUpstreamSlotid['upstream']];     
            }
            $arrTmp['map'][$arrUpstreamSlotid['upstream']]['app_id'] = $arrAppIdMap[$arrUpstreamSlotid['upstream']]; 
            $arrTmp['map'][$arrUpstreamSlotid['upstream']]['slot_id'] = $arrUpstreamSlotid['upstream_slot_id']; 
        }

        // 3 获取之前的 data_for_sdk表中此媒体的信息
        $arrSdkDataBefore = json_decode($this->dbutil->getSdkData($strAppId), true); 
        $arrSdkDataAfter[$slot_id] = $arrTmp;
        $arrUpdate = [
            'data' => json_encode($arrSdkDataAfter),
            'where' => "app_id='" . $app_id . "'",
        ];
        $arrRes = $this->dbutil->udpSdkData($arrUpdate);
        if ($arrRes['code'] !== 0) {
            log_message('error', 'update data_for_sdk failed');
        }

    }

    private function getSdkMediaInfo($strAppId) {
        $arrSelect = [
            'select' => 'data',
            'where' => "app_id='" . $strAppId . "'",
        ];
        $arrRes = $this->dbutil->getSdkData($arrSelect);
        var_dump($arrRes);exit;
    } 

}
