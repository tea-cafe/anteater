<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 媒体信息
 */

class MediaAppData extends CI_Controller {

    private $strJsonTest = '{"code":"0","desc":"成功","data":{"totalCount":2,"totalPage":1,"list":[{"appId":32057,"appName":"17k","platform":"H5","exposureCount":438,"clickCount":9,"clickRate":2.05,"consumeTotal":865700,"eCpm":null,"cpc":null,"actSucResponseCount":null,"slotRequestPv":null,"slotRequestUv":null},{"appId":32466,"appName":"雷锋军事","platform":"H5","exposureCount":3939841,"clickCount":56025,"clickRate":1.42,"consumeTotal":66921400,"eCpm":null,"cpc":null,"actSucResponseCount":null,"slotRequestPv":null,"slotRequestUv":null}],"sum":null}}';

    /*
     *
     */
    public function __construct() {
        parent::__construct();
    }

	/**
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        echo $this->strJsonTest;
	}
}
