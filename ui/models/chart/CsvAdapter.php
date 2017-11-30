<?php
class CsvAdapter extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->library('CsvReader');
    }

    public function bai() {
        $ret = $this->csvreader->import();
        $arrContent = $this->csvreader->read_file();
        if($ret == true) {
            $arrContent = $this->csvreader->read_file();
            var_dump($arrContent);
        }
    }
}
