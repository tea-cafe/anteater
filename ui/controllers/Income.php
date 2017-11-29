<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 收入明细
 */

class Income extends MY_Controller{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		/*
		$media_name_list = array(
			'0' => '雷锋军事',
			'1' => '3G门户',
			'2' => '娱乐王',
		);

		$this->load->library("DbUtil");
		for($a=1;$a<=12;$a++){
			foreach($media_name_list as $key => $value){
				$time = strlen($a) == 1 ? "0".$a : $a;
				$data[$key][$a]['time'] = strtotime("2017".$time);
				$data[$key][$a]['account'] = 'aaa@qq.com';
				$data[$key][$a]['media_name'] = $value;
				$data[$key][$a]['media_platform'] = 'H5';
				$data[$key][$a]['income'] = mt_rand(1000,10000).'.'.mt_rand(10,99);
				$data[$key][$a]['insert_time'] = strtotime(date("YmdHis"));
				$data[$key][$a]['status'] = mt_rand(1,5);
				$this->dbutil->setMonth($data[$key][$a]);
			}
			
		}
		*/
		$account = 'aaa@qq.com';
		$pageSize = 20;
		$currentPage = 1;
		$this->load->model("Finance");
		$this->Finance->getMonthBill($account,$pageSize,$currentPage);
	}
}
?>
