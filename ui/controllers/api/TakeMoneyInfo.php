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
		//header("Content-Type: application/json");

		/*
		$media_list = array(
			0 => array(
				'appid' => '13141',
				'media_name' => '雷锋军事',
				'media_platform' => 'H5',
				'time' => '2017-09',
				'money' => '13145.20',
			),
			1 => array(
				'appid' => '13142',
				'media_name' => '3G门户',
				'media_platform' => 'H5',
				'time' => '2017-09',
				'money' => '13145.20',
			),
			2 => array(
				'appid' => '13143',
				'media_name' => '17K',
				'media_platform' => 'H5',
				'time' => '2017-09',
				'money' => '13145.20',
			),
		);

		$info = array(
			0 => array(
				'a' => 'a',
				'b' => 'b',
				'c' => 'c',
			),
			1 => array(
				'a' => 'a',
				'b' => 'b',
				'c' => 'c',
			),
			2 => array(
				'a' => 'a',
				'b' => 'b',
				'c' => 'c',
			),
		);

		$account = array(
			0 => 'aaa@qq.com',
			1 => 'bbb@qq.com',
			2 => 'ccc@qq.com',
			3 => 'ddd@qq.com',
			4 => 'fff@qq.com',
		);
		$time = 1483200000;
		$this->load->library("DbUtil");
		for($a=0;$a<=365;$a++){
			$data[$a]['time'] = $time + $a*86400;
			$data[$a]['account'] = $account[mt_rand(0,4)];
			$data[$a]['number'] = $time.mt_rand(100,999);
			$data[$a]['money'] = mt_rand(1000,9999).'.'.mt_rand(10,99);
			$data[$a]['media_list'] = serialize($media_list);
			$data[$a]['info'] = serialize($info);
			$data[$a]['status'] = mt_rand(1,4);
		
			$this->dbutil->setMoney($data[$a]);
		}
		exit;
		*/

		$number = $this->input->get("number",true);
		if(empty($number) || strlen($number) != 13){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS);
		}

		$this->load->model('User');
		$account = $this->User->checkLogin();
		if(empty($account)){
			$account = 'ccc@qq.com';
		}else{
			$account = $account['email'];
		}

		$this->load->model("Finance");
		$result = $this->Finance->getTakeMoneyInfo($account,$number);
		
		if(empty($result) || count($result) == 0){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS);
		}
		
		return $this->outJson($result,ErrCode::OK,'提现单信息获取成功');
	}
}
?>
