<?php
	class Finance extends CI_Model{
		public function __construct(){
			parent::__construct();
		}


        /**
         * 获取提现列表
         */
		public function getTakeMoneyList($accId,$startDate,$endDate,$pageSize,$currentPage,$status){
			if(empty($status)){
				$statusStr = '';
			}else{
				$statusStr = ' AND status = '.$status;
			}

			if($currentPage == 1){
				$currentPage = 0;
			}else{
				$currentPage = ($currentPage - 1) * $pageSize;
            }

            /* 提现单查询 */
			$listWhere = array(
				'select' => 'id,time,account_id,number,money,status',
				'where' => 'time > '.$startDate.' and time < '.$endDate.' and account_id = "'.$accId.'"'.$statusStr,
				'order_by' => 'time',
				'limit' => empty($pagesize) || empty($currentpage) ? '0,20' : $currentpage.','.$pagesize,
            );
			$this->load->library("dbutil");
			$tmrlist = $this->dbutil->gettmr($listwhere);

            /* end */

            /* 分页信息查询 */
			$totalwhere = array(
				'select' => 'count(*)',
				'where' => 'time > '.$startdate.' and time < '.$enddate.' and account_id = "'.$accid.'"'.$statusstr,
			);

			$totalcount = $this->dbutil->gettmr($totalwhere);
            
            $pagination = array(
                'current' => empty($currentpage) ? '1':$currentpage,
                'pagesize' => $pagesize,
                'total' => $totalcount[0]['count(*)'],
            );
            /* end */

            /* 账户余额查询 */
            $balancewhere = array(
                'select' => 'money',
                'where' => 'account_id = "'.$accid.'" and status = "1"',
            );
            
            $accbalance = $this->dbutil->getaccbalance($balancewhere);
            /* end */
            if(empty($accbalance)){
                return [];
            }

            /* 财务认证状态 */
            $financewhere = array(
                'select' => 'check_status',
                'where' => 'account_id = "'.$accid.'"',
            );
            $strfinance = $this->dbutil->getaccount($financewhere);
            /* end */
			$result['list'] = $tmrlist;
            $result['balance'] = $accbalance[0]['money'];
            $result['finance_status'] = $strfinance[0]['check_status'];
			$result['pagination'] = $pagination;
			return $result;
		}

        /**
         * 获取月账单列表
         */
		public function getmonthlybill($accid,$pagesize,$currentpage){
            if($currentpage == 1){
				$currentpage = 0;
			}else{
				$currentpage = ($currentpage - 1) * $pagesize;
			}
			$listwhere = array(
				'select' => 'time,account_id,app_id,media_name,media_platform,money,status',
				'where' => 'account_id = "'.$accid.'"',
				'order_by' => 'time',
				'limit' => empty($pagesize) || empty($currentpage) ? '0,20' : $currentpage.','.$pagesize,
            );
			$this->load->library("dbutil");
			$data = $this->dbutil->getmonthly($listwhere);

            foreach($data as $k => $v){
                $data[$k]['time'] = date('y-m',$v['time']);
            }

			if(empty($data)){
				return $data;
			}	

			$totalwhere = array(
				'select' => 'count(*)',
				'where' => 'account_id = "'.$accid.'"',
			);

			$totalCount = $this->dbutil->getMonthly($totalWhere);

            $paginAtion = array(
                'current' => empty($currentPage) ? '1':$currentPage,
                'pageSize' => $pageSize,
                'total' => $totalCount[0]['count(*)'],
            );
			$result['list'] = $data;
			$result['pagination'] = $paginAtion;
			
			return $result;
		}

		/* 获取提现单详情 */
		public function getTakeMoneyInfo($accId,$number){
			$where = array(
				'select' => '',
				'where' => 'account_id = "'.$accId.'" AND number = '.$number,
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
			$listWhere = array(
				'select' => 'time,media_name,media_platform,money',
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND app_id = "'.$appid.'"',
				'order_by' => 'time',
				'limit' => '0,'.$limit,
			);
            
            $this->load->library("DbUtil");
			$data = $this->dbutil->getDaily($listWhere);

			if(empty($data)){
				return $data;
			}

			foreach($data as $key => $value){
				$data[$key]['time'] = date("Y-m-d",$value['time']);
			}
            
            $totalWhere = array(
				'select' => 'count(*)',
				'where' => 'time > '.$startDate.' AND time < '.$endDate.' AND app_id = "'.$appid.'"',
			);

			$totalCount = $this->dbutil->getDaily($totalWhere);
 
            $paginAtion = array(
                'current' => empty($currentPage) ? '1':$currentPage,
                'pageSize' => $limit,
                'total' => $totalCount[0]['count(*)'],
            );
			$result['list'] = $data;
			$result['pagination'] = $paginAtion;
			
			return $result;
		}

		/**
		 * 检查账户财务状态
		 */
		public function checkFinanceInfo($accId){
			$where = array(
				'select' => 'check_status',
				'where' => 'account_id = "'.$accId.'"',
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
		public function getAccountMoney($accId){
			$where = array(
				'select' => 'status,money',
				'where' => 'account_id = "'.$accId.'"',
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
		public function confirmTakeMoney($accId,$email,$money){
            /* 获取可以提现的月账单 */
            $billWhere = array(
				'select' => 'id,time,app_id,media_name,media_platform,money',
				'where' => 'account_id = "'.$accId.'" AND status = 1',
            );
			$this->load->library('DbUtil');
			$tmpList = $this->dbutil->getMonthly($billWhere);
			
			foreach($tmpList as $key => $value){
				$billList[$key]['id'] = $value['id'];
				$billList[$key]['time'] = date("Y-m",$value['time']);
				$billList[$key]['app_id'] = $value['app_id'];
				$billList[$key]['media_name'] = $value['media_name'];
				$billList[$key]['media_platform'] = $value['media_platform'];
				$billList[$key]['money'] = $value['money'];
            }

            /*获取媒体信息*/
			$infoWhere = array(
				'select' => 'email,contact_person,phone,bank_account,company_address,company,bank,bank_branch',
				'where' => 'account_id = "'.$accId.'"',
			);
			$tmpInfo = $this->dbutil->getAccount($infoWhere);
			$tmpInfo[0]['media'] = $billList[0]['media_name'].'等';
			$tmpInfo[0]['money'] = $money;
			$info['channel_info'] = $tmpInfo[0];
            
            /* 获取开票信息 */
            $this->config->load('company_invoice_info');
            $info['company_info'] = $this->config->item('invoice')['info'];
            $info['mail'] = $this->config->item('invoice')['mail'];
            $info['invoice_info'] = array(
                'money' => '',
                'code' => '',
                'number' => '',
            );

			$params = array(
				0 => array(
					'type' => 'insert',
					'tabName' => 'tmr',
					'data' => array(
						'time' => time(),
						'account_id' => $accId,
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
					'where' => 'account_id = "'.$accId.'"',
					'data' => array(
						'money' => 0,
						'update_time' => time(),
					),
				),
				2 => array(
					'type' => 'update',
					'tabName' => 'monthly',
				    'where' => 'account_id = "'.$accId.'" AND status = 1',
					'data' => array(
						'status' => '2',
						'update_time' => time(),
					),
				),
			);

            $TmrWhere = array(
 				'select' => 'id,time,account_id,number,money,status',
				'where' => 'account_id = "'.$accid.'"',
				'order_by' => 'time',
				'limit' => '0,20',
            );
            $result['TmrList'] = $this->duutil->getTmr($TmrWhere);

			$result['TmrStatus'] = $this->dbutil->sqlTrans($params);
                       
            return $result;
		}
	}
?>	
