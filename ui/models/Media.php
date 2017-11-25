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
        $arrParams['app_id'] = $this->dbutil->getAutoincrementId('media') + 1000; 
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
     * TODO 
     */
    public function getMediaLists() {
        $this->load->model('media/Lists');
        $arrData = $this->Lists->getLists();
        return $arrData;
    } 

}
