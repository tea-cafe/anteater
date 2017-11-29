<?php
class Media extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * @param array $arrParams
     * @return bool
     */
    public function insertMediaInfo($arrParams) {
        $this->load->library('DbUtil');
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
        $this->load->library('DbUtil');
        $arrRes = $this->dbutil->udpMedia($arrParams);
        if (!$arrRes) {
            return false;
        }
        return true;
    }

    /**
     * @param array
     * @return array 
     */
    public function getMediaLists($intAccountId, $pn = 1, $rn = 10, $intCount = 0) {
        $this->load->library('DbUtil');
        $arrSelectTotal = [
            'select' => 'count(*) as total',
            'where' => 'account_id=' . $intAccountId,
        ];
        $arrRes = $this->dbutil->getMedia($arrSelectTotal);
        $total = $arrRes[0]['total'];
        $arrSelect = [
            'select' => 'app_id,media_name,check_status,media_platform',
            'where' => 'account_id=' . $intAccountId,
            'limit' => $rn*($pn-1) . ',' . $rn,
        ];
        $arrRes = $this->dbutil->getMedia($arrSelect);
        return [
            'list' => $arrRes,
            'pagination' => [
                'total' => $total,
                'pageSize' => $rn,
                'current' => $pn,
            ],
        ];
    } 

}
