<?php
/**
 * Error code definition.
 *
 * System/framework/exception: the errno is less than 1000.
 *
 * | Module                | Specific Error Code  |
 * | 1000 (auto increment) | 000 (auto increment) |
 */
class ErrCode {

    const OK = 0;

    const ERR_SYSTEM     = 1;
    const ERR_INVALID_PARAMS = 2;

    /**
     * @param array  $arrResponse
     * @param int    $intErrCode
     * @param string $strErrMsg   Use the default error message if the parameter is not provided.
     *
     * @return array
     */
    public static function format($arrResponse, $intErrCode, $strErrMsg=null) {//{{{//
        if (is_null($strErrMsg)) {
            $strErrMsg = self::getDefaultErrMsg($intErrCode);
        }
        return [
            'code' => $intErrCode,
            'msg'  => $strErrMsg,
            'data' => $arrResponse,
        ];
    }//}}}//

    /**
     * @param int $intErrCode
     *
     * @return string
     */
    public static function getDefaultErrMsg($intErrCode) {
        switch ($intErrCode) {
            case self::OK:
                return '';
            case self::ERR_SYSTEM:
                return 'System error.';
            case self::ERR_INVALID_PARAMS:
                return 'Params error.';
            default:
                return 'Unknown error type.';
        }
    }

}
