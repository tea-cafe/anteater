<?php
/*使用AccountID当数组key，抑制Notice错误*/
error_reporting(E_ALL & ~E_NOTICE);
/**
 * @计费名Model
 */
class ChargingModel extends CI_Model {

	const STATUS_CODE = [
		'0' => '未申请',
		'1' => '等待审核',
		'2' => '审核成功',
		'3' => '审核失败',
	];

    public function __construct() {
        parent::__construct();
        $this->load->library('DbUtil');
    }

	/**
	 *@ 查询计费名申请状态
	 */
	public function getApplyStatus($params){
    	$where = array(
    		'select' => 'charging_status',
    		'where' => 'account_id="'.$params.'"',
    	);

    	$res = $this->dbutil->getCharging($where);

    	if(!$res){
    		$res[0]['charging_status'] = '0';
    	}
		$result['status'] = self::STATUS_CODE[$res[0]['charging_status']];
    	return $result;
    }

	/**
	 *@ 设置计费名申请
	 */
    public function setApply($params){
		$infoWhere = array(
			'select' => '*',
			'where' => 'account_id="'.$params.'"',
		);

		$info = $this->dbutil->getAccount($infoWhere);
		unset($info[0]['id']);
		unset($info[0]['passwd']);
		$info[0]['create_time'] = time();
		$info[0]['update_time'] = time();
		$info[0]['charging_status'] = '1';
		
		$res = $this->dbutil->setCharging($info[0]);
    	return $res;
	}

	public function getReportData($arrParams){
		$queryWhere = '';
		$level = 'charging';
		if(isset($arrParams['charging_name']) && !empty($arrParams['charging_name'])){
			$queryWhere = "charging_name='".$arrParams['charging_name']."' AND ";
			$level = 'days';
		}

		$where = array(
			'select' => '*',
			'where' => $queryWhere."account_id='".$arrParams['account_id']."' AND DATE_FORMAT(date,'%Y-%m-%d') >= DATE_FORMAT('".$arrParams['startDate']."','%Y-%m-%d') AND DATE_FORMAT(date,'%Y-%m-%d') <= DATE_FORMAT('".$arrParams['endDate']."','%Y-%m-%d') AND mark='2'",
		);

		$res = $this->dbutil->getChargingDataDaily($where);
		$res = $this->handleData($level,$res);

		if($arrParams['current'] == 1){
			$list = array_slice($res,0,$arrParams['pageSize']);
		}else{
			$startKey = ($arrParams['current'] - 1) * $arrParams['pageSize'];
			$list = array_slice($res,$startKey,$arrParams['pageSize']);
		}

		return [
			'list' => $list,
			'pagination' => [
				'total' => (int)count($res),
				'current' => (int)$arrParams['current'],
				'PageSize' => (int)$arrParams['pageSize'],
				'startDate' => $arrParams['startDate'],
				'endDate' => $arrParams['endDate'],
			],
		];
	}

	public function handleData($level,$data){
		switch($level){
			case "charging":
				$res = array();
				foreach($data as $key => $value){
					$chargingName = $value['charging_name'];
					$res[$chargingName]['charging_name'] = $chargingName;
					$res[$chargingName]['account_id'] = $value['account_id'];
					$res[$chargingName]['search_num'] += $value['search_num'];
					$res[$chargingName]['click_num'] += $value['click_num'];
					$res[$chargingName]['money'] += $value['money'];
				}

				foreach($res as $key => $value){
					$res[$key]['click_rate'] = (intval($value['search_num']) == 0) ? 0 : round($value['click_num']/$value['search_num']*100, 3);
				}
				$res = array_values($res);
				break;
			case "days":
				foreach($data as $key => $value){
					$data[$key]['click_rate'] = (intval($value['search_num']) == 0) ? 0 : round($value['click_num']/$value['search_num']*100, 3);
				}
				$res = $data;
				break;
		}

		return $res;
	}
}
?>
