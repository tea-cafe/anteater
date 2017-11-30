<?php
	class Finance extends CI_Model{
		public function __construct(){
			parent::__construct();
		}
	
		/*获取提现列表*/
		public function getTakeMoneyList($account,$startDate,$endDate,$pageSize,$currentPage,$status){
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
			
			if(empty($data)){
				return $data;
			}	
			
			foreach($data as $key => $value){
				$data[$key]['time'] = date("Y-m-d H:i:s",$value['time']);
				$data[$key]['media_list'] = unserialize($value['media_list']);
				$data[$key]['info'] = unserialize($value['info']);
			}

			$total_where = array(
				'select' => 'count(*)',
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND account = "'.$account.'"'.$statusStr,
				'order_by' => '',
				'limit' => '',
			);

			$totalCount = $this->dbutil->getMoney($total_where);
			$totalCount = $totalCount[0]['count(*)'];
			$totalPage = ceil($totalCount / $pageSize);

			$result['list'] = $data;
			$result['totalCount'] = $totalCount;
			$result['totalPage'] = $totalPage;
			$result['list'] = $data;
			return $result;
		}

		/*获取月账单列表*/
		public function getMonthlyBill($account,$pageSize,$currentPage){
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
			$data = $this->dbutil->getMonthly($where);

			if(empty($data)){
				return $data;
			}	

			$total_where = array(
				'select' => 'count(*)',
				'where' => 'account = "'.$account.'"',
				'order_by' => '',
				'limit' => '',
			);

			$totalCount = $this->dbutil->getMonthly($total_where);
			$totalCount = $totalCount[0]['count(*)'];
			$totalPage = ceil($totalCount / $pageSize);

			$result['list'] = $data;
			$result['totalCount'] = $totalCount;
			$result['totalPage'] = $totalPage;
			$result['list'] = $data;
			
			return $result;
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
			
			if(empty($data)){
				return $data;
			}

			$data[0]['media_list'] = unserialize($data[0]['media_list']);
			$data[0]['info'] = unserialize($data[0]['info']);

			return $data[0];
		}

		/*获取日账单列表*/
		public function getDailyBillList($appid,$startDate,$endDate,$limit){
			$where = array(
				'select' => '',
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND appid = '.$appid,
				'order_by' => 'time',
				'limit' => '0,'.$limit,
			);
			
			$this->load->library("DbUtil");
			$data = $this->dbutil->getDaily($where);
			
			if(empty($data)){
				return $data;
			}

			foreach($data as $key => $value){
				$data[$key]['time'] = date("Y-m-d",$value['time']);
			}
			
			$total_where = array(
				'select' => 'count(*)',
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND appid = '.$appid,
				'order_by' => '',
				'limit' => '',
			);

			$totalCount = $this->dbutil->getDaily($total_where);
			$totalCount = $totalCount[0]['count(*)'];
			$totalPage = ceil($totalCount / 20);

			$result['list'] = $data;
			$result['totalCount'] = $totalCount;
			$result['totalPage'] = $totalPage;
			$result['list'] = $data;
			
			return $result;
		}
	}
?>	
