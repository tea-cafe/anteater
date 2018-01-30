<?php
class AdSlot extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('DbUtil');
    }

    /**
     *
     */
    public function delSlot($strAccountId, $strAppId, $strSlotId) {
        $mark = 0;
        $arrSelect = [
            'select' => 'account_id,app_id',
            'where' => "slot_id='" . $strSlotId . "'",  
        ];
        $arrRes = $this->dbutil->getAdSlot($arrSelect);
        if (empty($arrRes)) {
            return -1;
        }
        foreach ($arrRes as $val) {
            if ($val['app_id'] == $strAppId
                && $val['account_id'] == $strAccountId) {
                $mark = 1;
                break;
            }
        }
        if ($mark === 0) {
            retrun -1;
        }

        $arrSelect = [
            'select' => 'data',
            'where' => "app_id='" . $strAppId . "'",  
        ];
        $arrRes = $this->dbutil->getSdkData($arrSelect);
        if (empty($arrRes)) {
            return -1;
        }
        $jsonData = $arrRes[0]['data'];
        $arrData = json_decode($jsonData, true);
        if (empty($arrData)) {
            return -2;
        }
        if (isset($arrData[$strSlotId])) {
            unset($arrData[$strSlotId]);
        }
        $jsonData = json_encode($arrData);
        $arrUpdate = [
            'data' => $jsonData,
            'where' => "app_id='" . $strAppId . "'",
        ];
        $res = $this->dbutil->udpSdkData($arrUpdate);
        if ($res['code'] == 0) {
            $arrDelete = [
                'where' => "app_id='" . $strAppId . "' AND slot_id='" . $strSlotId . "'",
            ];
            $this->dbutil->delAdSlot($arrDelete);
            return 0;
        }
        return -2;
    }

    /**
     *
     */
    public function modifySlotName($strAccountId, $strAppId, $strSlotId, $strSlotName) {
        $mark = 0;
        $arrSelect = [
            'select' => 'app_id',
            'where' => "account_id='" . $strAccountId . "'",  
        ];
        $arrRes = $this->dbutil->getMedia($arrSelect);
        if (empty($arrRes)) {
            return '';
        }
        foreach ($arrRes as $val) {
            if ($val['app_id'] == $strAppId) {
                $mark = 1;
                break;
            }
        }

        if ($mark === 0) {
            retrun -1;
        }

        $arrUpdate = [
            'slot_name' => $strSlotName,
            'where' => "app_id='" . $strAppId . "' AND slot_id='" . $strSlotId . "'",  
        ];
        $res = $this->dbutil->udpAdslot($arrUpdate);
        if ($res['code'] == 0) {
            return 0;
        }
        return -2;
    }

    /**
     *
     */
    public function getUpstreamSlotId($strAccountId, $intSlotId) {
        $arrSelect = [
            'select' => 'upstream_slot_id',
            'where' => 'slot_id=' . $intSlotId . " AND ad_upstream='" . "TUIA' AND account_id='" . $strAccountId . "'" ,
        ];
        $arrRes = $this->dbutil->getAdslotmap($arrSelect);
        if (empty($arrRes)) {
            return '';
        }
        return $arrRes[0]['upstream_slot_id'];
    }

    /**
     *
     */
    public function getAdSlotList($strAccountId, $pn = 1, $rn = 10, $strSlotName = '') {
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

            // 获取media_info的app_delivery_method
            $arrSelect = [
                'select' => 'app_id,app_secret,app_id_map,media_delivery_method',
                'where' => "account_id='" . $strAccountId . "'",
            ];
            $arrMediaInfo = $this->dbutil->getMedia($arrSelect);
            $arrMediaAppId2DeliverMethod = [];
            foreach ($arrMediaInfo as $val) {
                if (empty($val)
                    || empty($val['media_delivery_method'])
                    || empty($val['app_id_map'])) {
                    continue;
                }
                $arrMediaAppId2DeliverMethod[$val['app_id']]['delivery_method'] = $val['media_delivery_method']; 
                $arrMediaAppId2DeliverMethod[$val['app_id']]['app_secret'] = $val['app_secret'];
                $arrMediaAppId2DeliverMethod[$val['app_id']]['app_id_map'] = @json_decode($val['app_id_map'], true);
            }
            $this->config->load('style2platform_map');
            $arrStyleMap = $this->config->item('style2platform_map');
            foreach ($arrRes as &$arrSlot) {
                $arrSlot['media_delivery_method'] = $arrMediaAppId2DeliverMethod[$arrSlot['app_id']]['delivery_method'];
                $arrSlot['app_secret'] = $arrMediaAppId2DeliverMethod[$arrSlot['app_id']]['app_secret'];
                //TODO  目前只返回tuia 的 app id, 后续会根据类型来判断
                $arrSlot['upid'] = isset($arrMediaAppId2DeliverMethod[$arrSlot['app_id']]['app_id_map']['TUIA']) ? $arrMediaAppId2DeliverMethod[$arrSlot['app_id']]['app_id_map']['TUIA'] : ''; 
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
                'total' => intval($intCount),
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

        $bolRes = $this->InsertAdslot->distributePreSlotId(
            $arrPreSlotIds,
            intval($arrParams['slot_style']),
            intval($arrParams['slot_size']),
            $arrParams['app_id'],
            $arrParams['account_id'],
            $arrParams['slot_id'],
            $arrAppIdMap,
            $arrParams
        );
        return $bolRes;
    }

}
