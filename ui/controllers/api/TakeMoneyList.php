<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 获取提现明细列表
 */

class TakeMoneyList extends MY_Controller{
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		header('Content-type: application/json');
		
		$startDate = strtotime($this->input->get("startdate",true)) - 1;
		$endDate = strtotime($this->input->get("enddate",true)) + 86401;
		$pageSize = $this->input->get("pagesize",true);
		$currentPage = $this->input->get("currentpage",true);
		$status = $this->input->get("status",true);

		if(empty($startDate) || empty($endDate) || empty($pageSize)){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS);
		}

		$statusCode = array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
		);

		if(!empty($status) && empty($statusCode[$status])){
			$status = '';
		}

		$this->load->model('User');
		$account = $this->User->checkLogin();
		if(empty($account)){
			$account = 'ccc@qq.com';
		}else{
			$account = $account['email'];
		}

		$this->load->model("Finance");
		$result = $this->Finance->getTakeMoney($account,$startDate,$endDate,$pageSize,$currentPage,$status);
		
		if(empty($result) || count($result) == 0){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS);
		}

		return $this->outJson($result,ErrCode::OK,'数据获取成功');
	}
}
