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
        $this->load->helper(['form', 'url']);
    }

    public function index() {
        $this->load->view('upload_form', ['error' => '']);
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

        if (!$this->upload->do_upload('file')) {
            return $this->outJson('', ErrCode::ERR_UPLOAD);
        }
        $arrRes = $this->upload->data();

        $strImgUrl = str_replace(WEBROOT, '', $arrRes['full_path']);
        return $this->outJson(
            ['img_url' => $strImgUrl],
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

        if (!$this->upload->do_upload('file')) {
            return $this->outJson('', ErrCode::ERR_UPLOAD, '上传csv文件失败，请重试');
        }
        $arrRes = $this->upload->data();
        $strCsvUrl = str_replace(WEBROOT, '', $arrRes['full_path']);
        return $this->outJson(
            ['img_url' => $strCsvUrl],
            ErrCode::OK,
            '文件上传成功');
    }

    /**
     * upload app
     */
	public function uploadApp() {
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN, '会话已过期,请重新登录');
        } 

        $strAppId = $this->input->post('app_id', true);
        if (empty($strAppId)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        // 用户白名单过滤

        $arrUdpAppConf = $this->config->item('app');
        $arrUdpAppConf['file_name'] = md5($strAppId . $_FILES['file']['name']);
        $this->load->library('upload', $arrUdpAppConf);
        if (!$this->upload->do_upload('file')) {
            return $this->outJson('', ErrCode::ERR_UPLOAD, '上传app失败，请重试');
        }
        $arrRes = $this->upload->data();
        $strAppUrl = '/' . $arrRes['file_name'];
        $arrUpdate = [
            'app_verify_url' => $strAppUrl,
            'check_status' => 2,
            'where' => "app_id='" . $this->input->post('app_id', true) . "'",
        ];
        $this->load->model('Media');
        $bolRes = $this->Media->updateMediaInfo($arrUpdate);
        if (!$bolRes) {
            return $this->outJson('', ErrCode::ERR_UPLOAD, 'app地址生成失败，请重新上传');
        }
        return $this->outJson(
            ['app_url' => $strAppUrl],
            ErrCode::OK,
            'app上传成功');
    }

    /**
     * 仅限运营使用
     */
	public function uploadTxt() {
        if (empty($this->arrUser)) {
            return $this->outJson('', ErrCode::ERR_NOT_LOGIN, '会话已过期,请重新登录');
        } 

        $strAppId = $this->input->post('app_id', true);
        if (empty($strAppId)) {
            return $this->outJson('', ErrCode::ERR_INVALID_PARAMS); 
        }
        // 用户白名单过滤

        $arrUdpTxtConf = $this->config->item('txt');
        $arrUdpTxtConf['file_name'] = md5($strAppId . $_FILES['file']['name']);
        $this->load->library('upload', $arrUdpTxtConf);

        if (!$this->upload->do_upload('file')) {
            return $this->outJson('', ErrCode::ERR_UPLOAD, '上传app失败，请重试');
        }
        $arrRes = $this->upload->data();
        $strTxtUrl = '/' . $arrRes['file_name'];
        $arrUpdate = [
            'app_key' => $strTxtUrl,
            'check_status' => 1,
            'where' => "app_id='" . $this->input->post('app_id', true) . "'",
        ];
        $this->load->model('Media');
        $bolRes = $this->media->updateMediaInfo($arrUpdate);
        if (!$bolRes) {
            return $this->outJson('', ErrCode::ERR_UPLOAD, 'app地址生成失败，请重新上传');
        }
        return $this->outJson(
            ['app_key' => $strTxtUrl],
            ErrCode::OK,
            'app_key文件上传成功');
    }
}
