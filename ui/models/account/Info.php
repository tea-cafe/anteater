<?php
/**
 * getInfo 账户信息
 *
 */
class Info extends CI_Model {
    

    public function __construct() {
        parent::__construct();

    }

    /**
     * 获取媒体信息
     *
     * @return array
     */
    public function getInfo($intAccountId) {
        $this->load->library('DbUtil');
        $arrFields = [
            'select' => '
                company,
                contact_person,
                financial_object,
                collection_company,
                bussiness_license_num,
                bussiness_license_pic,
                phone,
                bank_account,
                bank,
                province,
                city,
                bank_branch,
                role_type,
                personal_name,
                bank_card_front_pic,
                bank_card_back_pic,
                company_address,
                contact_address,
                identity_card_num,
                identity_card_front,
                identity_card_back,
                account_open_permission,
                account_holder,
                remark
            ',
            'where' => 'account_id=' . $intAccountId, 
        ];
        $arrRes = $this->dbutil->getAccount($arrFields);
        if ($arrRes) {
            return $arrRes[0];
        }
        return $arrRes; 
    } 
}
