<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 获取提现明细信息
 */

class TakeMoney extends MY_Controller{
    
    const VALID_INVOICE_INFO_KEY = [
        'orderid',
        'number',
        'money',
    ];
    
    public function __construct(){
		parent::__construct();
		$this->load->model("Finance");
    }

    /* 获取提现单列表 */
	public function index(){
        if(empty($this->arrUser)){
            return $this->outJson('',ErrCode::ERR_NOT_LOGIN);
        }
        
        $startDate = strtotime($this->input->get("startdate",true));
        $endDate = strtotime($this->input->get("enddate",true));
        $pageSize = $this->input->get("pagesize",true);
		$currentPage = $this->input->get("currentpage",true);
        
        $startDate = empty($startDate) ? 1483200000 : $startDate-1;
        $endDate = empty($endDate) ? time() : $endDate+86401;
        $pageSize = empty($pageSize) ? 20 : $pageSize;
        $currentPage = empty($currentPage) ? 1 : $currentPage;

        /**
         * 状态筛选
        $status = $this->input->get("status",true);

		$statusCode = array(
			'0' => '0',
			'1' => '1',
			'2' => '2',
			'3' => '3',
		);

		if(!isset($status) && empty($statusCode[$status])){
			$status = '';
		}
        */

        $email = $this->arrUser['email'];
        $accId = $this->arrUser['account_id'];

		$result = $this->Finance->getTakeMoneyList($accId,$startDate,$endDate,$pageSize,$currentPage);
        
        if(empty($result)){
            return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'数据获取失败');
        }

        if(empty($result['list'])){
            return $this->outJson($result,ErrCode::OK,'暂无提现记录');
        }else{
            return $this->outJson($result,ErrCode::OK,'获取提现记录成功');
        }    
    }

	/**
	 * 提现单详情,如：地址等等
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

		$result = $this->Finance->getTakeMoneyInfo($accId,$number);

		if(empty($result) || count($result) == 0){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'参数错误');
		}
		
		return $this->outJson($result,ErrCode::OK,'提现单信息获取成功');
    }

    /**
     * 发票信息提交.
     */
    public function addInvoice(){
        if(empty($this->arrUser)){
            return $this->outJson('',ErrCode::ERR_NOT_LOGIN);
        }
        $arrPostParams = json_decode(file_get_contents('php://input'), true);
        $arrPostParams['orderid'] = intval($arrPostParams['orderid']);
        $arrPostParams['number'] = intval($arrPostParams['number']);
        $arrPostParams['money'] = floatval($arrPostParams['money']);

        if(count($arrPostParams) != count(self::VALID_INVOICE_INFO_KEY)){
            return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'参数错误');
        }
        foreach($arrPostParams as $key => $value){
            if(empty($value)){
                return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'参数错误');
            }
        }

        $accId = $this->arrUser['account_id'];
        $result = $this->Finance->addInvoiceInfo($accId,$arrPostParams);
        
        switch($result){
            case '0':
                return $this->outJson('',ErrCode::OK,'发票添加成功');
                break;
            case '1':
                return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'参数错误');
                break;
            case '2':
                return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'该发票已存在');
                break;
        }
    }

    /**
     * 删除发票
     */
    public function delInvoice(){
        if(empty($this->arrUser)){
            return $this->outJson('',ErrCode::ERR_NOT_LOGIN);
        }
        $arrPostParams = json_decode(file_get_contents('php://input'), true);
        $arrPostParams['orderid'] = intval($arrPostParams['orderid']);
        $arrPostParams['number'] = intval($arrPostParams['number']);
        
        foreach($arrPostParams as $key => $value){
            if(empty($value)){
                return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'参数错误');
            }
        }

        $accId = $this->arrUser['account_id'];
        $result = $this->Finance->delInvoiceInfo($accId,$arrPostParams);
        
        if($result){
                return $this->outJson('',ErrCode::OK,'发票删除成功');
        }else{
                return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'参数错误');
        }
    }
}
