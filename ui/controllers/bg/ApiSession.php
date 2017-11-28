<?php
class ApiSession extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function gen_session() {
        $_md = '{"apps":"android,cn.coupon.kfc,cn.coupon.mac,cn.wps.moffice_eng,com. MobileTicket,com.UCMobile,com.alipay.security.mobile.authenticator,co m.android.BBKClock,com.android.BBKCrontab,com.android.BBKPhoneIn structions,com.android.BBKTools,com.android.VideoPlayer,com.android. attachcamera,com.android.backupconfirm,com.android.bbk.lockscreen3 ","idfa":"AEBE52E7-03EE-455A-B3C4-E57283966239","imei":"355065053 311001","latitude":"104.07642","longitude":"38.6518","model":"MIMAX", "nt":"wifi","os":"Android","os_version":"7","vendor":"Xiaomi"}';
		$url = 'https://engine.tuia.cn/index/activity?appKey=%s&adslotId=%s&md=%s&timestamp=%s&nonce=%s&signature=%s';
		$appKey = '2i9jsRZNd6rdMzDPoduqm9ZQswqx';
		$adslotId = '2700';
        $md = base64_encode(gzencode($_md, 9));
        $timestamp = time();
        $nonce = rand(pow(10, 5), pow(10, 6)-1);
        $appSecret = '3WjQ2DZ1HFMjAt3occZzxWcsKwNXDVVMCVKCvLR';
        $strHash = "appSecret={$appSecret}&md={$md}"."&"."timestamp={$timestamp}&nonce={$nonce}";
		$signature = base64_encode(sha1($strHash, true));
        $api_url = sprintf($url, $appKey, $adslotId, urlencode($md), $timestamp, $nonce, $signature);
        $this->load->library('Curl');
        $this->curl->create($api_url);
        $str = $this->curl->execute();
        $data = @json_decode($str, true);
        var_dump($data);
        /*
        $base64nonce = base64_encode ( $_nonce );
        $sessionSecurity = hash ( 'sha256', $app_key . $_nonce, true );
        $base64Security = base64_encode ( $sessionSecurity );
         */

    }
}
