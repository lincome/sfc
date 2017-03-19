<?php
/*
                           _ooOoo_
                          o8888888o
                          88" . "88
                          (| -_- |)
                          O\  =  /O
                       ____/`---'\____
                     .'  \\|     |//  `.
                    /  \\|||  :  |||//  \
                   /  _||||| -:- |||||-  \
                   |   | \\\  -  /// |   |
                   | \_|  ''\---/''  |   |
                   \  .-\__  `-`  ___/-. /
                 ___`. .'  /--.--\  `. . __
              ."" '<  `.___\_<|>_/___.'  >'"".
             | | :  `- \`.;`\ _ /`;.`/ - ` : | |
             \  \ `-.   \_ __\ /__ _/   .-` /  /
        ======`-.____`-.___\_____/___.-`____.-'======
                           `=---='
        ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
                 佛祖保佑       永无BUG
*/

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller as BaseController;

/**
 * API控制器基类
 *
 * @author  binlee
 */
class Controller extends BaseController
{
    use Helpers, AccountHelper;


    /**
     *  结果返回
     * @param $errcode int 1=失败 0=成功
     * @param $data array 结果
     * @return json
     */
    protected function output($errcode, $data)
    {
        $outdata = array(
            'errcode' => $errcode,
            'data' => $data
        );
        return response()->json($outdata);
    }
}
