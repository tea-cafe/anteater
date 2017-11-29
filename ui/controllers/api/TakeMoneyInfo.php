<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	 * 提现单信息,如：地址等等
	 */
class TakeMoneyInfo extends MY_Controller{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		header("Content-Type: application/json");
		
		$number = $this->input->get("number",true);
		if(empty($number) || strlen($number) != 13){
			$data['code'] = 1;
			$data['msg'] = '提现单号错误';
			$data['data'] = '';

			echo json_encode($data);
			return false;
		}

		$account = 'bbb@qq.com';
		$this->load->model("Finance");
		$result = $this->Finance->getTakeMoneyInfo($account,$number);
		
		if(empty($result) || count($result) == 0){
			$data['code'] = 1;
			$data['msg'] = '提现单号错误';
			$data['data'] = '';

			echo json_encode($data);
			return false;
		}
		
		$data['code'] = 0;
		$data['msg'] = '获取提现信息成功';
		$data['data'] = $result;
		echo json_encode($data);
	}
}
?>
