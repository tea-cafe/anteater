<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	 * 获取收入明细 每日收入列表
	 */

class DailyBillList extends MY_Controller{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		//header("Content-Type: application/json");
		/*模拟测试数据
		$media = array(
			'0' => '雷锋军事',
			'1' => '3G门户',
			'2' => '爱八卦',
		);

		$appid = array(
			'0' => '13141',
			'1' => '13142',
			'2' => '13143',
		);

		$this->load->library("DbUtil");
		foreach($media as $key => $value){
			$time = 1504108800;
			for($a=1;$a<=90;$a++){
				$data[$key][$a]['time'] = $time + $a * 86400;
				$data[$key][$a]['account'] = 'aaa@qq.com';
				$data[$key][$a]['appid'] = $appid[$key];
				$data[$key][$a]['media_name'] = $value;
				$data[$key][$a]['media_platform'] = 'H5';
				$data[$key][$a]['income'] = mt_rand(100,999).'.'.mt_rand(10,99);
				$data[$key][$a]['insert_time'] = time();
			
				$this->dbutil->setDay($data[$key][$a]);
			}
		}
		exit;
		 */

		$appid = $this->input->get("appid",true);
		$startDate = strtotime($this->input->get("curDate",true)) - 1;
		$endDate = strtotime(date("Y-m-t",$startDate+1)) + 86400;
		$dayNumber = date("t",$startDate+1);
		
		if(empty($appid) || empty($startDate) || $startDate == -1){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS);
		}

		$this->load->model("Finance");
		$result = $this->Finance->getDailyBillList($appid,$startDate,$endDate,$dayNumber);
		if(empty($result) || count($result) == 0){
			$data['code'] = 1;
			$data['msg'] = '无详细账单';
			$data['data'] = '';
			
			echo json_encode($data);
			return false;
		}

		$data['code'] = 0;
		$data['msg'] = '获取日账单成功';
		$data['data'] = $result;
		echo json_encode($data);
	}
}
?>
