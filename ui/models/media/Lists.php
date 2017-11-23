<?php
/**
 * 媒体列表
 *
 */
class Lists extends CI_Model {
    

    public function __construct() {
        parent::__construct();

    }

    /**
     * 获取媒体信息
     *
     * @return array
     */
    public function getLists() {
        $strJsonTest = '{"code":"0","desc":"成功","data":{"totalCount":5,"totalPage":1,"list":[{"mediaId":23409,"appId":35740,"appName":"12313","industry":null,"checkStatus":0,"verifyStatus":0,"platform":"Android","createDate":"2017-09-16","appKey":"253KqhSfwwK316MzUb3S7gEKv7yj","appSecret":"3WqLCiXnN2dmyz1ZiJLwrMt7HmPuaH8Mt2Ewh8x","mediaFrozenStatus":0,"mediaFrozenReason":null},{"mediaId":23409,"appId":34352,"appName":"3G门户直投","industry":null,"checkStatus":0,"verifyStatus":0,"platform":"H5","createDate":"2017-08-21","appKey":"2ugtTuBy4RPrB5wxWyNQ8n5sYPPh","appSecret":"aBstS4oNnQXwT7SMek63bW8aUQ3bRtAtDUTDh","mediaFrozenStatus":0,"mediaFrozenReason":null},{"mediaId":23409,"appId":33287,"appName":"3g门户","industry":null,"checkStatus":1,"verifyStatus":0,"platform":"H5","createDate":"2017-08-01","appKey":"3fhAGjn23sFoMqHmTFDYGsLfXsUj","appSecret":"3XXL8XNJZvD3duhPPL6zomrAP8KF4X49n1SZKwe","mediaFrozenStatus":0,"mediaFrozenReason":null},{"mediaId":23409,"appId":32466,"appName":"雷锋军事","industry":null,"checkStatus":1,"verifyStatus":0,"platform":"H5","createDate":"2017-07-19","appKey":"LfZTVeo6dSkVKysPaDpt1j2RBTW","appSecret":"3XTgQQaD3s4xa9cg2RyCsbKY9gzncJGTdXbbSfo","mediaFrozenStatus":0,"mediaFrozenReason":null},{"mediaId":23409,"appId":32057,"appName":"17k","industry":null,"checkStatus":1,"verifyStatus":0,"platform":"H5","createDate":"2017-07-12","appKey":"2i9jsRZNd6rdMzDPoduqm9ZQswqx","appSecret":"3WjQ2DZ1HFMjAt3occZzxWcsKwNXDVVMCVKCvLR","mediaFrozenStatus":0,"mediaFrozenReason":null}],"sum":null}}'; 
        return json_decode($strJsonTest, true);
    } 
}
