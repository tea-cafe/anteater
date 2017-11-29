<?php
	class Finance extends CI_Model{
		public function __construct(){
			parent::__construct();
		}
	
		/*获取提现列表*/
		public function getTakeMoney($account,$startDate,$endDate,$pageSize,$currentPage,$status){
			if(empty($status)){
				$statusStr = '';
			}else{
				$statusStr = ' AND status = '.$status;
			}

			if($currentPage == 1){
				$currentPage = 0;
			}else{
				$currentPage = $currentPage * $pageSize;
			}

			//var_dump($status);
			$where = array(
				'select' => '',
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND account = "'.$account.'"'.$statusStr,
				'order_by' => 'time',
				'limit' => empty($pageSize) || empty($currentPage) ? '0,20' : $currentPage.','.$pageSize,
			);
			$this->load->library("DbUtil");
			$data = $this->dbutil->getMoney($where);
			foreach($data as $key => $value){
				$data[$key]['time'] = date("Y-m-d H:i:s",$value['time']);
				$data[$key]['bill_list'] = unserialize($value['bill_list']);
				$data[$key]['info'] = unserialize($value['info']);
			}
			return $data;
		}

		/*获取月账单列表*/
		public function getMonthBill($account,$pageSize,$currentPage){
			if($currentPage == 1){
				$currentPage = 0;
			}else{
				$currentPage = $currentPage * $pageSize;
			}
			
			$where = array(
				'select' => '',
				'where' => 'account = "'.$account.'"',
				'order_by' => 'time',
				'limit' => empty($pageSize) || empty($currentPage) ? '0,20' : $currentPage.','.$pageSize,
			);
			$this->load->library("DbUtil");
			$data = $this->dbutil->getMonth($where);
			
			return $data;
		}

		/* 获取提现单详情 */
		public function getTakeMoneyInfo($account,$number){
			$where = array(
				'select' => '',
				'where' => 'account = "'.$account.'" AND number = '.$number,
				'order_by' => '',
				'limit' => '',
			);

			$this->load->library("DbUtil");
			$data = $this->dbutil->getMoney($where);

			$data[0]['bill_list'] = unserialize($data[0]['bill_list']);
			$data[0]['info'] = unserialize($data[0]['info']);

			return $data[0];
		}

		/*获取日账单列表*/
		public function getDayBillList($appid,$startDate,$endDate,$limit){
			$where = array(
				'select' => '',
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND appid = '.$appid,
				'order_by' => 'time',
				'limit' => '0,'.$limit,
			);
			
			$this->load->library("DbUtil");
			$data = $this->dbutil->getDay($where);
			
			foreach($data as $key => $value){
				$data[$key]['time'] = date("Y-m-d",$value['time']);
			}
			
			return $data;
		}
	}
?>	
