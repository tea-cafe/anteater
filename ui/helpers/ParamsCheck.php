<?php 
/**
 * 参数校验
 * @param string
 * @param string
 * @return bool
 */
if (!function_exists('ParamsCheck')) {
    function ParamsCheck($key, $val){ 
        switch($key) {
            case 'app_id':
                if (preg_match('#^[a-zA-Z0-9]#', $val)
                    || strlen($val) !== 7) {
                    return false;
                }
                break;
            case 'email':
                if (!preg_match('#^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$#', $val)) {
                    return false;
                }
                break;
            case 'phone':
                if (!preg_match('#^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\\d{8}$#', $val)) {
                    return false;
                }
                break;
            case 'app_platform':
                if (!preg_match('#^[0-9]#')) {
                    return false;
                }
                break;
            case 'media_platform':
                if (!in_array($val, ['Android', 'iOS', 'H5'])) {
                    return false;
                }
            case 'url':
                if (!preg_match('#^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$#', $val)) {
                    return fasle;
                }
                break;
            case 'app_detail_url':

            default:
                return true;
        }
        return true;
    }

}

