<?php
class AdSlot extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('DbUtil');
    }

    public function insertAdSlotInfo($arrParams) {

        $arrCheckMediaLegal = [
            'select' => 'app_id,media_platform,',
            'where' => "app_id='" . $arrParams['app_id'] . "' AND media_name='" . $arrParams['media_name'] . "' AND check_status=2",
            'limit' => '1',
        ];

        // TODO 后续做好审核通过appid 列表的缓存，减少数据库读取
        $arrCheckRes = $this->dbutil->getMedia($arrCheckMediaLegal);
        if (empty($arrCheckRes[0])
            || empty($arrCheckRes[0]['app_id'])) {
            return false;
        }


        // 生成 slot_id,
        $arrParams['slot_id'] = $this->dbutil->getAutoincrementId('adslot');

        $arrParams['app_id'] = $arrCheckRes[0]['app_id'];
        // 查询预分配slotid的表 获取上游 slotid, 写入 adslot_map 不需要返回. 保证以后slot如果要改，只用改slot_map就行了。 
        $bolRes = $this->insertSlotidMap(
            $arrParams['app_id'],
            $arrParams['slot_type'],
            $arrParams['slot_style'],
            $arrParams['slot_size'],
            $arrParams['slot_id']
        );
        if ($bolRes === false) {
            return false;
        }
        $arrRes = $this->dbutil->setAdSlot($arrParams);
        return $arrRes;
    }

    /**
     * 在adslot_map 插入此slotid 对应的上游slotid记录，与几个渠道就有几条记录, 便于反查
     */
    private function insertSlotidMap($strAppid, $strSlotType, $strSlotStyle, $strSlotSize, $strSlotid) {
        // 查询pre_adslot 获取可用的 上游 slot_id
        $arrSlotidsForAppid = [];
        $arrRes = $this->dbutil->getPreadslot(
            [
                'select' => 'app_id,data',
                'where' => "app_id='" . $strAppid . "'",
            ]
        );
        $arrPreSlotids = json_decode($arrRes[0]['data'], true); 
        foreach($arrPreSlotids as $upstream => &$arrType) {
            if (!empty($arrType[$strSlotType][$strSlotStyle][$strSlotSize])) {
                $arrTmp = $arrType[$strSlotType][$strSlotStyle][$strSlotSize];
                foreach($arrTmp as $adslot => $used) {
                    if ($used === 0) {
                        $arrSlotidsForAppid[] = [
                            'upstream' => $upstream,
                            'upstream_slot_id' => $adslot,
                        ];
                        $arrTmp[$adslot] = 1;
                        break;
                    }
                }
                $arrType[$strSlotType][$strSlotStyle][$strSlotSize] = $arrTmp;
            }
        } 

        if (empty($arrSlotidsForAppid)) {
            return false;
        }

        // TODO 更新pre_adslot
        //$this->dbutil->udpPreadslot();

        // 插入slot_id映射记录到 adslot_map
        $sql = 'INSERT INTO adslot_map(slot_id,app_id,upstream,upstream_slot_id,create_time,update_time) VALUES';
        foreach($arrSlotidsForAppid as $val) {
            $sql .= "(" . $strSlotid . ",'" . $strAppid . "','" . $val['upstream'] . "','" . $val['upstream_slot_id'] . "'," .  time() . "," . time() . "),";
        }
        $sql = substr($sql, 0, -1);
        $bolRes = $this->dbutil->query($sql);
        return $bolRes;
    }
}
