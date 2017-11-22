<?php
/**
 * getXXX($arrParams)
 * $arrParams = [
 *     'select' => 'username,passwd',
 *     'where' => 'create_time>0 AND update_time>0',
 *     'order_by' => 'passwd DESC',
 *     'limit' => '0,1',
 * ];
 *
 * setXXX($arrParams)
 * $arrParams = [
 *     'username' => 'aaa',
 *     'phone' => 'bbb',
 *     ... ... 
 * ];
 */
class DbUtil {

    const TAB_ACCOUNT               = 'account_info';
    const TAB_MEDIA                 = 'media_info';
    const TAB_AD_SLOT               = 'ad_slot_info';
    const TAB_MEDIA_PROFIT_SHARE    = 'media_profit_share';
    const TAB_AD_SLOT_PROFIT_SHARE  = 'adslot_profit_share';

    const TAB_MAP = [
        'account'   => self::TAB_ACCOUNT,
        'media'     => self::TAB_MEDIA,
        'adslot'    => self::TAB_AD_SLOT,
        'mps'       => self::TAB_MEDIA_PROFIT_SHARE,
        'adsps'     => self::TAB_AD_SLOT_PROFIT_SHARE, 
    ];

    public static $instance;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
    }

    /**
     * @param string $strFuncName
     * @param array $arrParams
     * @return array
     */
    public function __call($strFuncName, $arrParams = []) {
        $strTabName = preg_match('#(get|set)([A-Z].*)#', $strFuncName, $arrAcT);
        if (empty($arrAcT[1])
            || empty($arrAcT[2])
            || (!in_array(strtolower($arrAcT[2]), array_keys(self::TAB_MAP)))) {
            throw new Exception('DbUtil has no [method|table] : [' . $arrAcT[1] . ']|[' . $arrAcT[2] . ']');
        }
        return $this->$arrAcT[1](self::TAB_MAP[strtolower($arrAcT[2])], $arrParams[0]);
    }

    /**
     *
     */
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new DbUtil();
        }
        return self::$instance;
    }

    /**
     * @param string $strTabName
     * @param array $arrParams
     * @return array
     */
    private function get($strTabName, $arrParams) {
        if (empty($arrParams['limit'])) {
            $arrParams['limit'] = '0,1';
        }
        foreach ($arrParams as $act => $sqlPart) {
            if ($act === 'limit') {
                $arrLimit = explode(',', $sqlPart);
                // ci limit 参数和 sql 相反
                $this->CI->db->limit($arrLimit[1], $arrLimit[0]);
            }
            $this->CI->db->$act($sqlPart);
        }
        $objRes = $this->CI->db->get($strTabName);
        return $objRes->result_array();
    }


    /**
     * @param string $strTabName
     * @param array $arrParams
     * @return bool
     */
    private function set($strTabName, $arrParams) {
        $arrParams['create_time'] = time();
        $arrParams['update_time'] = time();
        $objRes = $this->CI->db->insert($strTabName, $arrParams);
        return $objRes;
    }
}
