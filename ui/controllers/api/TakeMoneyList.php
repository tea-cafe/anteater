<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 获取提现明细列表
 */

class TakeMoneyList extends CI_Controller{
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
			$data['code'] = 1;
			$data['msg'] = '参数不合法';
			$data['data'] = '';
			
			echo json_encode($data);
			return false;
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
		$this->load->model("Finance");
		$account = 'aaa@qq.com';
		$result = $this->Finance->getTakeMoney($account,$startDate,$endDate,$pageSize,$currentPage,$status);
		
		if(empty($result) || count($result) == 0){
			$data['code'] = 0;
			$data['msg'] = "数据获取成功";
			$data['data'] = '';

			echo json_encode($data);
			return false;
		}

		$data['code'] = 0;
		$data['msg'] = "数据获取成功";
		$data['data'] = $result;
		
		echo json_encode($data);
	}
}
