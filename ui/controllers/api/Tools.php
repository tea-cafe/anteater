<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 账户信息 
 */

class Tools extends MY_Controller {

    const KEY_IMG_URL_SALT = 'Qspjv5$E@Vkj7fZb';

    public function __construct() {
        parent::__construct();
        $this->config->load('upload');
        $this->load->helper('form');
        $this->load->helper(array('form', 'url'));
    }

    public function index() {
        $this->load->view('upload_form', array('error' => ' ' ));
    }

	/**
     *
	 */
	public function uploadImg() {
        $this->load->model('User');
        $arrUser = $this->User->checkLogin();
        if (empty($arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN, '会话已过期,请重新登录');
        } 

        $arrUdpImgConf = $this->config->item('img');
        $arrUdpImgConf['file_name'] = md5($arrUser['email'] . self::KEY_IMG_URL_SALT . $_FILES['id']);
        $this->load->library('upload', $arrUdpImgConf);

        if (!$this->upload->do_upload('userfile')) {
            return $this->outJson('', ErrCode::ERR_UPLOAD);
        }
        $arrRes = $this->upload->data();

        $strImgUrl = str_replace(WEBROOT, '', $arrRes['full_path']);
        return $this->outJson([
            'img_url' => $strImgUrl],
            ErrCode::OK,
            '图片上传成功');
    }

    /**
     * upload csv
     */
	public function uploadCsv() {
        $this->load->model('User');
        $arrUser = $this->User->checkLogin();
        if (empty($arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN, '会话已过期,请重新登录');
        } 

        // 用户白名单过滤

        $arrUdpCsvConf = $this->config->item('csv');
        $this->load->library('upload', $arrUdpCsvConf);

        if (!$this->upload->do_upload('userfile')) {
            return $this->outJson('', ErrCode::ERR_UPLOAD, '上传csv文件失败，请重试');
        }
        $arrRes = $this->upload->data();
        return $this->outJson('', ErrCode::OK, '文件上传成功');
    }
}
