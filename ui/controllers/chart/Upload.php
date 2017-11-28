<?php
class Upload extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function bd() {
        $this->load->library('CsvReader');
        $this->csvreader->import();
        echo 1;
    }

}
