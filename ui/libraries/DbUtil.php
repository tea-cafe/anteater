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
 *
 * udpXXX($arrParams)
 * $arrParams = [
 *     'email' => 'xxx',
 *     'name' => 'xxx',
 *     ...
 *     'where' => 'account_id=1 and app_id=1',
 * ];
 */
class DbUtil {

    const TAB_ACCOUNT               = 'account_info';
    const TAB_MEDIA                 = 'media_info';
    const TAB_AD_SLOT               = 'ad_slot_info';
    const TAB_MEDIA_PROFIT_SHARE    = 'media_profit_share';
    const TAB_AD_SLOT_PROFIT_SHARE  = 'adslot_profit_share';
	const TAB_TAKE_MONEY_RECORD		= 'take_money_record';
	const TAB_MONTHLY_BILL			= 'month_bill';
	const TAB_DAILY_BILL			= 'daily_bill';

    const TAB_MAP = [
        'account'   => self::TAB_ACCOUNT,
        'media'     => self::TAB_MEDIA,
        'adslot'    => self::TAB_AD_SLOT,
        'mps'       => self::TAB_MEDIA_PROFIT_SHARE,
		'adsps'     => self::TAB_AD_SLOT_PROFIT_SHARE, 
		'money'		=> self::TAB_TAKE_MONEY_RECORD,
		'monthly'	=> self::TAB_MONTHLY_BILL,
		'daily'		=> self::TAB_DAILY_BILL,
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
        $strTabName = preg_match('#(get|set|udp)([A-Z].*)#', $strFuncName, $arrAcT);
        if (empty($arrAcT[1])
            || empty($arrAcT[2])
            || (!in_array(strtolower($arrAcT[2]), array_keys(self::TAB_MAP)))) {
            throw new Exception('DbUtil has no [method|table] : [' . $arrAcT[1] . ']|[' . $arrAcT[2] . ']');
        }
        return $this->{$arrAcT[1]}(self::TAB_MAP[strtolower($arrAcT[2])], $arrParams[0]);
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
				continue;
			}
            $this->CI->db->$act($sqlPart);
        }
        $objRes = $this->CI->db->get($strTabName);
        if (empty($objRes)) {
            return [];
        }
        $arrRes = $objRes->result_array();
        if (!empty($arrRes[0])) {
            return $arrRes;
        }
        return $arrRes;
    }


    /**
     * @param string $strTabName
     * @param array $arrParams
     * @return bool
     */
    private function set($strTabName, $arrParams) {
        $arrParams['create_time'] = time();
        $arrParams['update_time'] = time();
        $this->CI->db->insert($strTabName, $arrParams);
        $arrRes = $this->CI->db->error();
        $arrRes['message'] = $this->formatErrMessage($arrRes);
        return $arrRes;
    }

    /**
     *
     */
    private function udp($strTabName, $arrParams) {
        $arrParams['update_time'] = time();
        $strWhere = $arrParams['where'];
        unset($arrParams['where']);
        foreach ($arrParams as $key => $val) {
            $this->CI->db->set($key, $val);
        }
        $this->CI->db->where($strWhere);
        $this->CI->db->update($strTabName);
        $arrRes = $this->CI->db->error();
        return $arrRes;
    }

    /**
     * @param array $arrRes
     * @return string
     */
    private function formatErrMessage($arrRes) {
        $strPattern = '#\'(.*)\'#';
        switch ($arrRes['code']) {
            case 1062:
                preg_match($strPattern, $arrRes['message'], $arrOut);
                return $arrOut[1];
            default:
                return '';
        }
    }

    /**
     * @param string $strSql
     * @return array
     */
    public function query($strSql) {
        $objRes = $this->CI->db->query($strSql);
        $arrRes = $objRes->result_array();
        return $arrRes;
    }

    /**
     * @param string $strTabKey
     * @return string
     */
    public function getAutoincrementId($strTabKey) {
        $strTabName = self::TAB_MAP[$strTabKey];
        $strSql = "SELECT AUTO_INCREMENT FROM information_schema.tables where table_name='$strTabName'";
        $objRes = $this->CI->db->query($strSql);
        $arrRes = $objRes->result_array();
        if (empty($arrRes)) {
            return 0;
        }
        return intval($arrRes[0]['AUTO_INCREMENT']);
    }
}
