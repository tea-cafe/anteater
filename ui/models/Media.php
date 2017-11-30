<?php
class Media extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('DbUtil');
    }

    /**
     * @param array $arrParams
     * @return bool
     */
    public function insertMediaInfo($arrParams) {
        $strMd5Info = empty($arrParams['app_package_name']) ? md5($arrParams['url']) : md5($arrParams['app_package_name']);
        $arrParams['app_id'] = substr($strMd5Info, 27, 5) . $this->dbutil->getAutoincrementId('media') . rand(0,9);
        $arrRes = $this->dbutil->setMedia($arrParams);
        if ($arrRes['code'] !== 0) {
            if ($arrRes['code'] === 1062) {
                log_message('error', 'Duplicate entry of ' . $arrRes['message']);
            }
            return false;
        }
        return true;
    }

    /**
     * @param array $arrParams
     * @return bool
     */
    public function updateMediaInfo($arrParams) {
        $arrRes = $this->dbutil->udpMedia($arrParams);
        if (!$arrRes) {
            return false;
        }
        return true;
    }

    /**
     *
     */
    public function getMediaSlotList($strAppId) {
        $arrSelect = [
            'select' => 'valid_slot_ids',
            'where' => "appid='" . $strAppId . "'",
        ];
        $arrRes = $this->dbutil->getMedia($arrSelect);
        return $arrRes;
    }

    /**
     * @param array
     * @return array 
     */
    public function getMediaLists($intAccountId, $pn = 1, $rn = 10, $intCount = 0, $condition='') {
        $this->load->library('DbUtil');
        if ($intCount === 0) {
            $arrSelect = [
                'select' => 'count(*) as total',
                'where' => 'account_id=' . $intAccountId,
            ];
            $arrRes = $this->dbutil->getMedia($arrSelect);
            $intCount = $arrRes[0]['total'];
        }
        $arrSelect = [
            'select' => 'app_id,media_name,check_status,media_platform',
            'where' => 'account_id=' . $intAccountId,
            'limit' => $rn*($pn-1) . ',' . $rn,
        ];
        if (!empty($condition)) {
            $arrSelect['where'] .= " AND media_name like '%" . $condition . "%'"; 
        }
        $arrRes = $this->dbutil->getMedia($arrSelect);
        return [
            'list' => $arrRes,
            'pagination' => [
                'total' => $intCount,
                'pageSize' => $rn,
                'current' => $pn,
            ],
        ];
    } 

}
