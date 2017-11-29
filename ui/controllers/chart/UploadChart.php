<?php
class UploadChart extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['form', 'url']);
    }

    public function index() {
        $this->load->view('upload_csv', array('error' => ''));
    }
    public function _remap($method, $params = []) {

    }
    public function bd() {
        $this->load->library('CsvReader');
        $ret = $this->csvreader->import();
        if($ret == true) {
            $arrContent = $this->csvreader->read_file();
            var_dump($arrContent);
        }
    }

}
