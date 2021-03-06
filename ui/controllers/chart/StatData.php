<?php
class StatData extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('chart/StatDataModel');
    }

    /**
     * 获取按日期汇总数据列表
     * type 取值Acct, Media, Slot
     */
    public function getStatSumDataList() {//{{{//
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }
        $arrParams = $this->input->get(NULL, TRUE);
        if(empty($arrParams['startDate'])
            || empty($arrParams['endDate'])
            || empty($arrParams['type'])
            || empty($arrParams['statId'])
            || !in_array($arrParams['type'], ['Media', 'Slot'])) {
            return $this->outJson([], ErrCode::ERR_INVALID_PARAMS);
        }
        $arrParams['pn'] = empty($arrParams['currentPage']) ? 1 : $arrParams['currentPage'];
        $arrParams['rn'] = empty($arrParams['pageSize']) ? 10 : $arrParams['pageSize'];
        $arrParams['method'] = 'getUsr'.$arrParams['type'].'Sum';
        $arrParams['lastday'] = $arrParams['endDate'];//date("Y-m-d",strtotime("-1 day"));
        $arrParams['account_id'] = $this->arrUser['account_id'];

        $arrList = $this->StatDataModel->getSumDataList($arrParams);
        return $arrList?$this->outJson($arrList, ErrCode::OK) : $this->outJson([], ErrCode::OK);
    }//}}}//

    /**
     * 获取daily数据列表
     */
    public function getStatDailyDataList() {//{{{//
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }
        $arrParams = $this->input->get(NULL, TRUE);
        if(empty($arrParams['startDate'])
            || empty($arrParams['endDate'])
            || empty($arrParams['type'])
            || empty($arrParams['statId'])
            || !in_array($arrParams['type'], ['Media', 'Slot'])) {
            return $this->outJson([], ErrCode::ERR_INVALID_PARAMS);
        }
        $arrParams['pn'] = empty($arrParams['currentPage']) ? 1 : $arrParams['currentPage'];
        $arrParams['rn'] = empty($arrParams['pageSize']) ? 10 : $arrParams['pageSize'];
        $arrParams['method'] = 'getUsr'.$arrParams['type'].'Sum';
        $arrParams['account_id'] = $this->arrUser['account_id'];

        $arrList = $this->StatDataModel->getDailyDataList($arrParams);
        return $arrList?$this->outJson($arrList, ErrCode::OK) : $this->outJson([], ErrCode::OK);
    }//}}}//

    public function exportStatSum() {
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN);
        }
        $arrParams = $this->input->get(NULL, TRUE);
        if(empty($arrParams['startDate'])
            || empty($arrParams['endDate'])
            || empty($arrParams['type'])
            || empty($arrParams['statId'])
            || !in_array($arrParams['type'], ['Media', 'Slot'])) {
            return $this->outJson([], ErrCode::ERR_INVALID_PARAMS);
        }
        $arrParams['method'] = 'getUsr'.$arrParams['type'].'Sum';
        $arrParams['account_id'] = $this->arrUser['account_id'];
        $strFileName = $arrParams['type'].$arrParams['startDate'].'_'.$arrParams['endDate'].'.csv';

        $strCsv = $this->StatDataModel->getCsvSumData($arrParams);
        $this->export_csv($strFileName, $strCsv);
    }

    public function export_csv($filename,$data) { 
		header("Content-type:text/csv"); 
		header("Content-Disposition:attachment;filename=".$filename); 
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0'); 
		header('Expires:0'); 
		header('Pragma:public'); 
		echo $data; 
	}
}
