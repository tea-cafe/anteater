<?php
/**
 * 自定义Controller基类
 */
class MY_Controller extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
    }


    /**
     * json 输出
     *
     * @param $array
     * @bool $bolJsonpSwitch
     */
    protected function outJson($arrData, $intErrCode, $strErrMsg=null,$bolJsonpSwitch = false) {
        $arrData = ErrCode::format($arrData, $intErrCode, $strErrMsg);
        echo json_encode($arrData); 
    } 

}
