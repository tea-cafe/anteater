<?php
	class Finance extends CI_Model{
		public function __construct(){
			parent::__construct();
		}


		/*获取提现列表*/
		public function getTakeMoneyList($account_id,$startDate,$endDate,$pageSize,$currentPage,$status){
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

            /* 提现单查询 */
			$where = array(
				'select' => '',
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND account_id = '.$account_id.$statusStr,
				'order_by' => 'time',
				'limit' => empty($pageSize) || empty($currentPage) ? '0,20' : $currentPage.','.$pageSize,
            );
			$this->load->library("DbUtil");
			$tmrList = $this->dbutil->getTmr($where);

            if(!empty($tmrList)){
                foreach($data as $key => $value){
                    $tmrList[$key]['time'] = date("Y-m-d H:i:s",$value['time']);
                    $tmrList[$key]['bill_list'] = unserialize($value['bill_list']);
                    $tmrList[$key]['info'] = unserialize($value['info']);
                }
            }
            /* end */

            /* 分页信息查询 */
			$total_where = array(
				'select' => 'count(*)',
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND account_id = "'.$account_id.'"'.$statusStr,
				'order_by' => '',
				'limit' => '',
			);

			$totalCount = $this->dbutil->getTmr($total_where);
			$totalCount = $totalCount[0]['count(*)'];
			$totalPage = ceil($totalCount / $pageSize);
            /* end */

            /* 账户余额查询 */
            $balance_where = array(
                'select' => 'money',
                'where' => 'account_id = '.$account_id.' AND status = "1"',
            );

            $AccBalance = $this->dbutil->getAccBalance($balance_where);
            /* end */

            /* 财务认证状态 */
            $finance_where = array(
                'select' => 'check_status',
                'where' => 'account_id = '.$account_id,
            );
            $strFinance = $this->dbutil->getAccount($finance_where);
            /* end */
            
			$result['list'] = $tmrList;
            $result['balance'] = $AccBalance[0]['money'];
            $result['finance_status'] = $strFinance[0]['check_status'];
			$result['totalCount'] = $totalCount;
			$result['totalPage'] = $totalPage;
			return $result;
		}

		/*获取月账单列表*/
		public function getMonthlyBill($account_id,$pageSize,$currentPage){
			if($currentPage == 1){
				$currentPage = 0;
			}else{
				$currentPage = $currentPage * $pageSize;
			}

			$where = array(
				'select' => 'time,account_id,app_id,media_name,media_platform,money,status',
				'where' => 'account_id = '.$account_id,
				'order_by' => 'time',
				'limit' => empty($pageSize) || empty($currentPage) ? '0,20' : $currentPage.','.$pageSize,
            );
			$this->load->library("DbUtil");
			$data = $this->dbutil->getMonthly($where);

            foreach($data as $k => $v){
                $data[$k]['time'] = date('Y-m',$v['time']);
            }

			if(empty($data)){
				return $data;
			}	

			$total_where = array(
				'select' => 'count(*)',
				'where' => 'account_id = "'.$account_id.'"',
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
		public function getTakeMoneyInfo($account_id,$number){
			$where = array(
				'select' => '',
				'where' => 'account_id = "'.$account_id.'" AND number = '.$number,
				'order_by' => '',
				'limit' => '',
			);

			$this->load->library("DbUtil");
			$data = $this->dbutil->getTmr($where);
			
			if(empty($data)){
				return $data;
			}

			$data[0]['bill_list'] = unserialize($data[0]['bill_list']);
			$data[0]['info'] = unserialize($data[0]['info']);

			return $data[0];
		}

		/*获取日账单列表*/
		public function getDailyBillList($appid,$startDate,$endDate,$limit){
			$where = array(
				'select' => 'time,media_name,media_platform,money',
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND app_id = "'.$appid.'"',
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
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND app_id = "'.$appid.'"',
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

		/**
		 * 检查账户财务状态
		 */
		public function checkFinanceInfo($account_id){
			$where = array(
				'select' => 'check_status',
				'where' => 'account_id = "'.$account_id.'"',
			);
			$this->load->library('DbUtil');
			$status = $this->dbutil->getAccount($where);
			$status = $status[0]['check_status'];
			
			if($status === '2'){
				return $status;
			}else{
				return [];
			}
		}

		/**
		 * 查询账户余额
		 */
		public function getAccountMoney($account_id){
			$where = array(
				'select' => 'status,money',
				'where' => 'account_id = "'.$account_id.'"',
			);

			$this->load->library('DbUtil');
			$result = $this->dbutil->getAccBalance($where);
			
			if(empty($result)){
				return [];
			}

			$data['money'] = floatval($result[0]['money']);
			$data['status'] = (int)$result[0]['status'];
			
			return $data;
		}

		/**
		 * 提现操作
		 */
		public function confirmTakeMoney($account_id,$email,$money){
            /* 查询最后一次提现时间 */
            $end_where = array(
                'select' => 'time',
                'where' => 'account_id = '.$account_id.' AND status = "2"',
                'order_by' => 'time desc',
                'limit' => '0,1',
            );
            $endTime = $this->dbutil->getTmr($end_where);
            if(empty($endTime)){
                $startDate = 1483200000;
            }else{
                $startDate = $endTime[0]['time'];
            } 
            $endDate = time();

            /* 获取可以提现的月账单 */
            $billWhere = array(
				'select' => 'time,appid,media_name,media_platform,money',
				'where' => 'time > '.$startDate.' AND time <'.$endDate.' AND account_id = "'.$account_id.'"',
				'order_by' => 'time',
			);
			$this->load->library('DbUtil');
			$tmpList = $this->dbutil->getMonthly($billWhere);
			
			foreach($tmpList as $key => $value){
				$billList[$key]['time'] = date("Y-m",$value['time']);
				$billList[$key]['appid'] = $value['appid'];
				$billList[$key]['media_name'] = $value['media_name'];
				$billList[$key]['media_platform'] = $value['media_platform'];
				$billList[$key]['money'] = $value['money'];
			}

            /*获取媒体信息*/
			$infoWhere = array(
				'select' => 'email,contact_person,phone,bank_account,company_address,company,bank,bank_branch',
				'where' => 'account_id = "'.$account_id.'"',
			);
			$tmpInfo = $this->dbutil->getAccount($infoWhere);
			$tmpInfo[0]['media'] = $billList[0]['media_name'].'等';
			$tmpInfo[0]['money'] = $money;
			$info['channel_info'] = $tmpInfo[0];
            
            /* 获取开票信息 */
            $this->config->load('company_invoice_info');
            $info['company_info'] = $this->config->item('invoice');

			$params = array(
				0 => array(
					'type' => 'insert',
					'tabName' => 'tmr',
					'data' => array(
						'time' => time(),
						'email' => $email,
						'number' => date("YmdHi").mt_rand(101,999),
						'money' => $money,
						'bill_list' => serialize($billList),
						'info' => serialize($info),
						'remark' => '',
						'status' => '1',
						'create_time' => time(),
						'update_time' => time(),
					),
				),
				1 => array(
					'type' => 'update',
					'tabName' => 'accbalance',
					'where' => 'account_id = "'.$account_id.'"',
					'data' => array(
						'money' => 0,
						'update_time' => time(),
					),
				),
				2 => array(
					'type' => 'update',
					'tabName' => 'monthly',
					'where' => 'time > '.$startDate.' AND time <'.$endDate.' AND account_id = "'.$account_id.'"',
					'data' => array(
						'status' => '2',
						'update_time' => time(),
					),
				),
			);
			
			$result = $this->dbutil->sqlTrans($params);
			
			return $result;
		}
	}
?>	
