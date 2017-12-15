<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 获取提现明细信息
 */

class TakeMoney extends MY_Controller{
	public function __construct(){
		parent::__construct();
    }

    /* 获取提现单列表 */
	public function index(){
        if(empty($this->arrUser)){
            return $this->outJson('',ErrCode::ERR_NOT_LOGIN);
        }
        
        $startDate = strtotime($this->input->get("startdate",true));
        $startDate = empty($startDate) ? 1483200000 : $startDate-1;
        $endDate = strtotime($this->input->get("enddate",true));
        $endDate = empty($endDate) ? time() : $endDate+86401;
        $pageSize = $this->input->get("pagesize",true);
        $pageSize = empty($pageSize) ? 20 : $pageSize;
		$currentPage = $this->input->get("currentpage",true);
        $currentPage = empty($currentPage) ? 1 : $currentPage;
        $status = $this->input->get("status",true);

		$statusCode = array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
		);

		if(!empty($status) && empty($statusCode[$status])){
			$status = '';
		}

        $email = $this->arrUser['email'];
        $accId = $this->arrUser['account_id'];
        
		$this->load->model("Finance");
		$result = $this->Finance->getTakeMoneyList($accId,$startDate,$endDate,$pageSize,$currentPage,$status);
        
        if(empty($result)){
            return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'数据获取失败');
        }
        return $this->outJson($result,ErrCode::OK,'数据获取成功');
    }

	/**
	 * 提现单信息,如：地址等等
	 */
	public function content(){
        if(empty($this->arrUser)){
            return $this->outJson('',ErrCode::ERR_NOT_LOGIN);
        }

		$number = $this->input->get("number",true);
		if(empty($number) || strlen($number) != 15){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS);
		}

        $accId = $this->arrUser['account_id'];
        $email = $this->arrUser['email'];

		$this->load->model("Finance");
		$result = $this->Finance->getTakeMoneyInfo($accId,$number);

		if(empty($result) || count($result) == 0){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS);
		}
		
		return $this->outJson($result,ErrCode::OK,'提现单信息获取成功');
	}
}
