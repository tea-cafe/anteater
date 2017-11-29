<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 获取收入明细 月账单列表
 */

class MonthBillList extends MY_Controller{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		header('Content-type: application/json');
		
		$pageSize = $this->input->get("pagesize",true);
		$currentPage = $this->input->get("currentpage",true);

		if(empty($pageSize) || empty($currentPage)){
			$data['code'] = 1;
			$data['msg'] = '参数不合法';
			$data['data'] = '';

			echo json_encode($data);
			return false;
		}

		$account = 'aaa@qq.com';
		$this->load->model("Finance");
		$result = $this->Finance->getMonthBill($account,$pageSize,$currentPage);
		
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
?>
