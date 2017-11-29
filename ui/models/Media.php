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
        $intAutoIncrementId = $this->dbutil->getAutoincrementId('media');
        if ($intAutoIncrementId === 0) {
            return false;
        }
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
     *
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
     * TODO 
     */
    public function getMediaLists($intAccountId) {
        $this->load->model('media/Lists');
        $arrData = $this->Lists->getLists($intAccountId);
        return $arrData;
    } 

}
