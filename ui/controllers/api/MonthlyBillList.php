<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 获取收入明细 月账单列表
 */

class MonthlyBillList extends MY_Controller{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		header('Content-type: application/json');

		/*
		$media_list = array(
			'0' => '雷锋军事',
			'1' => '3G门户',
			'2' => '娱乐网',
		);

		$appid = array(
			'0' => '13141',
			'1' => '13142',
			'2' => '13143',
		);


		$this->load->library("DbUtil");
		foreach($media_list as $key => $value){
			for($a=1;$a<=12;$a++){
				$date = strlen($a) == 1 ? '0'.$a : $a;
				$data[$key][$a]['time'] = strtotime('2017-'.$date.'-01');
				$data[$key][$a]['account'] = 'aaa@qq.com';
				$data[$key][$a]['appid'] = $appid[$key];
				$data[$key][$a]['media_name'] = $value;
				$data[$key][$a]['media_platform'] = 'H5';
				$data[$key][$a]['income'] = mt_rand(1000,9999).'.'.mt_rand(10,99);
				$data[$key][$a]['status'] = mt_rand(1,5);
				$this->dbutil->setMonthly($data[$key][$a]);
			}
		}
		var_dump($data);
		exit;
		*/
		
		$pageSize = $this->input->get("pagesize",true);
		$currentPage = $this->input->get("currentpage",true);
		if(empty($pageSize) || empty($currentPage)){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS);
		}


		$this->load->model('User');
		$account = $this->User->checkLogin();
		if(empty($account)){
			$account = 'aaa@qq.com';
		}else{
			$account = $account['email'];
		}

		$this->load->model("Finance");
		$result = $this->Finance->getMonthlyBill($account,$pageSize,$currentPage);
		
		if(empty($result) || count($result) == 0){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS);
		}

		return $this->outJson($result,ErrCode::OK,'月账单列表获取成功');
	}
}
?>
