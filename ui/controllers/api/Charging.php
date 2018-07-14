<?php
/**
 * 用户登录接口
 */

class Charging extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('ChargingModel');
    }

    /**
     * 获取计费名申请状态
     */
	public function applyStatus(){
		if(empty($this->arrUser)){
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }
        
        $res = $this->ChargingModel->getApplyStatus($this->arrUser['account_id']);
        if(!$res){
            return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'查询失败');
        }

        return $this->outJson($res,ErrCode::OK,'查询成功');
    }

	/**
	 * @计费名申请
	 */
    public function apply(){
        if(empty($this->arrUser)){
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }
        
        $res = $this->ChargingModel->setApply($this->arrUser['account_id']);
		switch($res['code']){
			case 1062:
				$res['status'] = '等待审核';
				unset($res['code']);
				unset($res['message']);
				return $this->outJson($res,ErrCode::ERR_INVALID_PARAMS,'申请失败，已经申请');
				break;
			case 0:
				$res['status'] = '等待审核';
				unset($res['code']);
				unset($res['message']);
				return $this->outJson($res,ErrCode::OK,'申请成功，待审核');
				break;
			default:
				$res['status'] = '未申请'; 
				unset($res['code']);
				unset($res['message']);
				return $this->outJson($res,ErrCode::ERR_INVALID_PARAMS,'申请失败，可能您已经申请');
		}
    }

    public function reportData(){
        if(empty($this->arrUser)){
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }

        $arrParams = $this->input->get(NULL,TRUE);
		$arrParams['pageSize'] = isset($arrParams['pageSize']) ? $arrParams['pageSize'] : 10;
		$arrParams['current'] = isset($arrParams['current']) ? $arrParams['current'] : 1;
        $arrParams['account_id'] = $this->arrUser['account_id'];

		if(!isset($arrParams['startDate']) || empty($arrParams['startDate'])){
			$time = date("Y-m-d");
			$arrParams['startDate'] = date("Y-m-d",strtotime("$time -31 day"));
			$arrParams['endDate'] = date("Y-m-d",strtotime("$time -1 day"));
		}

		$res = $this->ChargingModel->getReportData($arrParams);

		if(empty($res)){
			return $this->outJson('',ErrCode::ERR_INVALID_PARAMS,'查询失败');
		}

		return $this->outJson($res,ErrCode::OK,'获取数据成功');
	}
}
