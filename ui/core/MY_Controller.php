<?php
/**
 * 自定义Controller基类
 */
class MY_Controller extends CI_Controller {
    
    protected $CI;

    public function __construct() {
        parent::__construct();
    }


    /**
     * json 输出
     *
     * @param $array
     * @bool $bolJsonpSwitch
     */
    protected function outJson($arrData, $bolJsonpSwitch = false) {
        // err code
        echo json_encode($arrData); 
    } 

}
