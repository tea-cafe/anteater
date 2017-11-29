<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 提现明细
 */

class TakeMoney extends MY_Controller{
	public function __construct(){
		parent::__construct();
	}

	/*提现明细*/
	public function index(){
		/*首次进入默认时间区间为1个月*/
		$startDate = strtotime(date("Y-m-d",strtotime("-30 day")));
		$endDate = strtotime(date("Y-m-d",strtotime("+1 day")));
		$account = 'bbb@qq.com';
		$pageSize = '20';
		$currentPage = '0';
		$status = '';

		/*
		$media_list = array(
			'0' => 'aaa@qq.com',
			'1' => 'bbb@qq.com',
			'2' => 'ccc@qq.com',
			'3' => 'ddd@qq.com',
			'4' => 'eee@qq.com',
		);

		$bill_list = array(
			'0' => '2017-0717kH5月账单',
			'1' => '2017-07雷锋军事H5月账单',
			'2' => '2017-073G门户H5月账单',
		);

		$info = array(
			'jiesuan' => array(
				'0' => 'a',
				'1' => 'b',
				'2' => 'c',
			),
			'fapiao' => array(
				'0' => 'a',
				'1' => 'b',
				'2' => 'c',
			),
			'summary' => array(
				'0' => 'a',
				'1' => 'b',
				'2' => 'c',
			),
		);

		$this->load->library("DbUtil");
		for($a=1;$a<=90;$a++){
			$media_key = mt_rand(0,4);
			$data[$a]['time'] = 1504109043 + $a*86400;
			$data[$a]['account'] = $media_list[$media_key];
			$data[$a]['number'] = 1504109043 + $a*86400 . mt_rand(200,999);
			$data[$a]['money'] = mt_rand(10000,100000).'.'.mt_rand(0,99);
			$data[$a]['bill_list'] = serialize($bill_list);
			$data[$a]['info'] = serialize($info);
			$data[$a]['status'] = mt_rand(1,4);

		$this->dbutil->setMoney($data[$a]);
		}
		*/

		$this->load->model('Finance');
		$data = $this->Finance->getTakeMoney($account,$startDate,$endDate,$pageSize,$currentPage,$status);
		var_dump($data);
		$this->load->library('Smartylib');
		$this->smartylib->display('caiwu.tpl');
	}
}
?>
