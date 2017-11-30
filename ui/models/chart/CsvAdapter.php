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
            $this->process_content($arrContent);
		}
	}

    private function process_content($arrContent) {
        $chunkData = array_chunk($arrContent , 5000);
		$count = count($chunkData);
		for ($i = 0; $i < $count; $i++) {
			$insertRows = array();
			foreach($chunkData[$i] as $value){
				$string = mb_convert_encoding(trim(strip_tags($value)), 'utf-8', 'gbk');
				$v = explode(',', trim($string));
				$row = array();
				$row['cdate']    = empty($v[0]) ? date('Y-m-d') : date('Y-m-d',strtotime($v[0]));
				$row['business'] = $v[1];
				$row['project']  = $v[2];
				$row['shopname'] = $v[3];
				$row['shopid']   = $v[4];
				$row['fanli']    = $v[5];
				$row['fb']	 = $v[6] * 100;
				$row['jifen']    = $v[7];
				$sqlString       = '('."'".implode( "','", $row ) . "'".')'; //批量
				$insertRows[]    = $sqlString;
			}
			$result = $this->addDetail($insertRows); //批量将sql插入数据库。
		}
	}

	public function addDetail($rows){  
        if(empty($rows)){
            return false;
        }
        //数据量较大,采取批量插入  
        $data = implode(',', $rows);  
        $sql = "INSERT IGNORE INTO tb_account_detail(cdate,business,project,shopname,shopid,fanli,fb,jifen) VALUES {$data}";
        echo $sql;exit;
        $result = $this->query($sql);
        return true;
    } 
}
