<?php

/**
 * helpers.php.
 * 工具函数
 */


/**
 * @param $uri
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */
/*function admin_url($uri)
{
    return url('admin/' . $uri);
}*/

/**
 * @param $name
 * @return mixed
 */
function admin_view($name)
{
    $args = func_get_args();
    $args[0] = 'admin.' . $name;

    return call_user_func_array('view', $args);
}

/**
 * 上传文件读取路径OSS
 *
 * @param string $path 数据库中存放的图片路径
 * @return string
 */
function uploads_url($path = '', $oss_type = '')
{
    if (config('filesystems.default') == 'oss') {
        if ($oss_type == '') {
            return config('app.upload_url') . $path;
        } else {
            return config('app.upload_url') . $path . $oss_type;
        }
    } else {
        if ($path === false) {
            return config('app.upload_url');
        }
        if ($path != '') {
            $img = config('app.upload_url') . $path;

            if (!file_exists(public_path() . '/uploads/' . $path)) {
                $img = config('app.assets_url') . 'images/' . config('image.image_default');
            }
            return $img;
        } else {
            return config('app.assets_url') . 'images/' . config('image.image_default');
        }
    }

}

/**
 * 返回当前公众号.
 * @return \Illuminate\Foundation\Application|mixed
 */
function account()
{
    return app('bee.account_service');
}

/**
 * 返回当前地址.
 * @param $tag
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */
function make_api_url($tag)
{
    return url('/api?t=' . $tag);
}

/**
 * 返回当前顾客（微信端）.
 * @return \Illuminate\Foundation\Application|mixed
 */
function customer()
{
    return app('bee.customer_service');
}

/**
 * 手机号码打码
 * @param $str 手机号码
 * @return string
 */
function maskPhone($str)
{
    if (empty($str)) return '';
    $len = strlen($str);
    $start = round($len / 3);
    $end = $start * 2;
    return substr($str, 0, $start) . sprintf("%'*" . ($end - $start) . "s", "") . substr($str, $end);
}


/**
 * 导出数据为excel表格
 * @param array $data 一个二维数组,结构如同从数据库查出来的数组
 * @param array $title excel的第一行标题,一个数组,如果为空则没有标题
 * @param string $filename 下载的文件名
 * eg:exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
 */
function exportexcel($data = array(), $title = array(), $filename = 'report')
{
    header("Content-type:application/octet-stream");
    header("Accept-Ranges:bytes");
    header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition:attachment;filename=" . $filename . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    if (!empty($title)) {
        foreach ($title as $k => $v) {
            $title[$k] = iconv("UTF-8", "GB2312", $v);
        }
        $title = implode("\t", $title);
        echo "$title\n";
    }
    if (!empty($data)) {
        foreach ($data as $key => $val) {
            foreach ($val as $ck => $cv) {
                $data[$key][$ck] = iconv("UTF-8", "GB2312//IGNORE", $cv);
            }
            $data[$key] = implode("\t", $data[$key]);

        }
        echo implode("\n", $data);

    }
}

/**
 * 导出数据、图片为excel表格 用户导出
 * @param array $data 一个二维数组,结构如同从数据库查出来的数组
 * @param array $title excel的第一行标题,一个数组,如果为空则没有标题
 * @param string $filename 下载的文件名
 * eg:exportexcel($arr,array('id','账户','密码','昵称'),'文件名!');
 */
function exportImageExcel($data = array(), $title = array(), $filename = 'user')
{
    $excel = new PHPExcel();
    $objDrawing = new PHPExcel_Worksheet_Drawing();

    /*设置文本对齐方式*/
    $excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objActSheet = $excel->getActiveSheet();

    $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N');
    /*设置表头数据*/
    $tableheader = array('姓名', '组名', '二维码');
    /*填充表格表头*/
    for ($i = 0; $i < count($tableheader); $i++) {
        $excel->getActiveSheet()->setCellValue("$letter[$i]1", "$tableheader[$i]");
    }
    /*设置表格数据*/
//    $data = array(
//        array('小王', '男', '20', 'CS12', 'test.jpg'),
//        array('小李', '女', '21', 'CS12', 'test.jpg'),
//        array('小周', '男', '22', 'CS12', 'test.jpg'),
//        array('小赵', '女', '23', 'CS12', 'test.jpg'),
//        array('小张', '男', '24', 'CS12', 'test.jpg')
//    );

    /*填充表格内容*/
    for ($i = 0; $i < count($data); $i++) {
        $j = $i + 2;
        /*设置表格宽度*/
        $objActSheet->getColumnDimension('A')->setWidth(30);
        $objActSheet->getColumnDimension('B')->setWidth(30);
        $objActSheet->getColumnDimension('C')->setWidth(30);
        $objActSheet->getColumnDimension('D')->setWidth(30);
        $objActSheet->getColumnDimension('E')->setWidth(30);
        /*设置表格高度*/
        $excel->getActiveSheet()->getRowDimension($j)->setRowHeight(100);
        /*向每行单元格插入数据*/
        for ($row = 0; $row < count($data[$i]); $row++) {
            if ($row == (count($data[$i]) - 1)) {
                /*实例化插入图片类*/
                $objDrawing = new PHPExcel_Worksheet_Drawing();
                /*设置图片路径*/
                Log::info($data[$i][$row]);
                $objDrawing->setPath($data[$i][$row]);
                /*设置图片高度*/
                $objDrawing->setHeight(130);
                /*设置图片要插入的单元格*/
                $objDrawing->setCoordinates("$letter[$row]$j");
                /*设置图片所在单元格的格式*/
                $objDrawing->setOffsetX(120);
                $objDrawing->setRotation(40);
                $objDrawing->getShadow()->setVisible(true);
                $objDrawing->getShadow()->setDirection(50);
                $objDrawing->setWorksheet($excel->getActiveSheet());
                continue;
            }
            $excel->getActiveSheet()->setCellValue("$letter[$row]$j", $data[$i][$row]);
        }
    }

    $write = new PHPExcel_Writer_Excel5($excel);
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
    header("Content-Type:application/force-download");
    header("Content-Type:application/vnd.ms-execl");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");;
    header('Content-Disposition:attachment;filename="' . $filename . '.xls"');
    header("Content-Transfer-Encoding:binary");
    $write->save('php://output');
}


/**
 * 读取Excel表格
 * @param $filePath Excel文件路径
 * @param $field 需要保存的字段 array('id','username','password')
 * @param string $keyfield 读取Excel那列 array('A','B','C')
 * @param string $inputFileType
 * @return array|bool
 *
 * eg: $filePath = '1.xls';
 * $field = array('id', 'username', 'password');
 * $column = array( 'A', 'B', 'C');
 * readExcel($filePath,$field,$column);
 */
function readExcel($filePath, $field, $keyfield = '', $inputFileType = 'Excel5')
{

    require_once '../excel/PHPExcel.php';

    $reader = PHPExcel_IOFactory::createReader($inputFileType); //设置以Excel5格式(Excel97-2003工作簿)
    $PHPExcel = $reader->load($filePath); // 载入excel文件
    $sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
    $highestRow = $sheet->getHighestRow(); // 取得总行数
    $highestColumm = $sheet->getHighestColumn(); // 取得总列数

    $all_title = array(); // 筛选EXCEL中包含可入库的标题列
    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
        $row = 1;//第一行标题
        $tmp_title = $sheet->getCell($column . $row)->getValue();

        // 筛选入库的字段对应的标题
        if (in_array($tmp_title, $field)) {

            // 构建坐标和字段的关系
            $key = array_search($tmp_title, $field);
            $all_title[$key] = $column;
        }
    }

    // 不存在关键字段，不能进行数据处理
    if (!isset($all_title[$keyfield])) {
        return false;
    }

    // 构建入库的数据源格式
    for ($j = 2; $j <= $highestRow; $j++) {
        $colData = array();

        foreach ($all_title as $k => $v) {
            $colData[$k] = $sheet->getCell($v . $j)->getValue();
        }

        // 关键字段的内容不能为空
        if (!empty($keyfield)) {
            if (!empty($colData[$keyfield])) {
                $excelData[] = $colData;
            }
        }

    }

    $result_r = array(
        'row_old' => $highestRow,
        'row_new' => count($excelData),
        'data' => $excelData

    );

    return $result_r;
}

/**
 * 根据文件名判断文件类型
 * @param $filename
 * @return int|string
 */
function get_filetype($filename)
{
    $filetypes = array(
        'av' => array('av', 'wmv', 'wav', 'wma', 'avi'),
        'real' => array('rm', 'rmvb'),
        'mp3' => array('mp3', 'mp4'),
        'binary' => array('dat', 'bin'),
        'flash' => array('swf', 'fla', 'as'),
        'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
        //'office' => array('doc', 'xls', 'ppt'),
        'pdf' => array('pdf'),
        'text' => array('c', 'cpp', 'cc'),
        'zip' => array('tar', 'zip', 'gz', 'tar.gz', 'rar', '7z', 'bz'),
        'book' => array('chm'),
        'torrent' => array('bt', 'torrent'),
        'font' => array('ttf', 'font', 'fon'),
        'xls' => array('xls', 'xlsx')
    );

    $filename = strtolower($filename);
    $ext = substr(strrchr($filename, '.'), 1);
    foreach ($filetypes as $type => $arr) {
        if (in_array($ext, $arr)) {
            return $type;
        }
    }
    return 'unknown';
}

/**
 * 远程下载图片
 * @param $url
 * @param $save_dir
 * @param string $filename
 * @param int $type
 * @return array
 */
function getImage($url, $save_dir, $filename = '', $type = 0)
{
    if (trim($url) == '') {
        return array('file_name' => '', 'save_path' => '', 'error' => 1);
    }
    if (trim($save_dir) == '') {
        $save_dir = './';
    }
    if (trim($filename) == '') {//保存文件名
        $ext = strrchr($url, '.');
        $ext = $ext == '' ? '.jpg' : $ext;
        $filename = get_img_name($ext);
    }
    if (0 !== strrpos($save_dir, '/')) {
        $save_dir .= '/';
    }
    //创建保存目录
    if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
        return array('file_name' => '', 'save_path' => '', 'error' => 5);
    }
    //获取远程文件所采用的方法
    if ($type) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $img = curl_exec($ch);
        curl_close($ch);
    } else {
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
    }
    //$size=strlen($img);
    //文件大小
    $fp2 = fopen($save_dir . $filename, 'a');
    fwrite($fp2, $img);
    fclose($fp2);
    unset($img, $url);
    return array('file_name' => $filename, 'save_path' => $save_dir . $filename, 'error' => 0);
}

/**
 *  随机获得文件名
 * @param $ext
 * @return string
 */
function get_img_name($ext)
{
    list($t1, $t2) = explode(' ', microtime());
    $ttt = (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    return $ttt . '_' . uniqid() . $ext;
}

/**
 * @param $url
 * @param $data
 * @return mixed
 */
function http_post($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // curl_setopt( $ch, CURLOPT_HEADER, 1 );

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $resp = curl_exec($ch);
    curl_close($ch);
    return $resp;
}

/**
 * 远程获取
 * @param $url
 * @return mixed
 */
function http_get($url)
{
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    return $data;
}

/**
 *  判断是否为当天
 * @param $date
 * @return bool
 */
function isCurrentDay($date)
{
    if (!is_numeric($date)) {
        $a_ux = strtotime($date);
    } else {
        $a_ux = $date;
    }
    $a_date = date('Y-m-d', $a_ux);
    $b_date = date('Y-m-d');
    return $a_date == $b_date ? true : false;
}

/**
 *  调试
 * @param $array
 */
function mtrace($array)
{
    echo '<pre>';
    print_r($array);
    exit();
}

/**
 * 查看最后一条执行的sql 需要在执行sql前加上》DB::connection()->enableQueryLog();
 * @return mixed
 */
function lastSql()
{
    $sql = DB::getQueryLog();
    $query = end($sql);
    return $query;
}


/**
 * 数组排序
 * @param $arrays
 * @param $sort_key
 * @param int $sort_order
 * @param int $sort_type
 * @return array|bool
 */
function my_sort($arrays, $sort_key, $sort_order = SORT_ASC, $sort_type = SORT_NUMERIC)
{
    if (is_array($arrays)) {
        foreach ($arrays as $array) {
            if (is_array($array)) {
                $key_arrays[] = $array[$sort_key];
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
    array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
    return $arrays;
}

/**
 * 计算时长
 * @param $start_time
 * @param $end_time
 * @return float|string
 */
function hours_min($start_time, $end_time)
{
    if (empty($start_time) && empty($end_time)) {
        return 0;
    }
    $start_time = strtotime($start_time);
    $end_time = strtotime($end_time);
    if ($start_time < $end_time) {
        $tmp = $end_time;
        $end_time = $start_time;
        $start_time = $tmp;
    }
    $sec = $start_time - $end_time;
    $sec = round($sec / 60);
    $min = str_pad($sec % 60, 2, 0, STR_PAD_LEFT);
    $hours_min = floor($sec / 60);
    $min != 0 && $hours_min .= ' 小时 ' . $min . ' 分';
    return $hours_min;
}


/**
 * 字符串截取
 * @param string $string
 * @param integer $length
 * @param string $suffix
 * @return string
 */
function strCut($string, $length, $suffix = '...')
{
    $resultString = '';
    $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
    $strLength = strlen($string);
    for ($i = 0; (($i < $strLength) && ($length > 0)); $i++) {
        if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
            if ($length < 1.0) {
                break;
            }
            $resultString .= substr($string, $i, $number);
            $length -= 1.0;
            $i += $number - 1;
        } else {
            $resultString .= substr($string, $i, 1);
            $length -= 0.5;
        }
    }
    $resultString = htmlspecialchars($resultString, ENT_QUOTES, 'UTF-8');
    if ($i < $strLength) {
        $resultString .= $suffix;
    }
    return $resultString;
}

/**
 * 取时间年月日
 * @param $created_at
 * @return float|string
 */
function dateYMD($created_at)
{
    $created_at = date('Y-m-d', strtotime($created_at));
    return $created_at;
}

/**
 * 创建多级目录
 * @param $dir
 * @param int $mode
 * @return bool
 */
function makeDir($dir, $mode = 0777)
{
    if (!is_dir($dir)) {
        makeDir(dirname($dir), $mode);
        $rs = mkdir($dir, $mode);
        if (!$rs) {
            return $rs;
        }
    }
    return true;
}


/**
 * 二维数组去重
 * @param $arr
 * @param $key
 * @return array
 */
function assoc_unique($arr, $key)
{
    $rAr = array();
    for ($i = 0; $i < count($arr); $i++) {
        if (!isset($rAr[$arr[$i][$key]])) {
            $rAr[$arr[$i][$key]] = $arr[$i];
        }
    }
    return array_values($rAr);
}

/**
 * 生成唯一用户订单号
 * 18位数：2016082321211 + 82358  时间（14位） + 随机（4位）
 * @return string
 */
function buildNo()
{
    $order_id_main = date('YmdHis') . rand(10, 99);
    //订单号码主体长度
    $order_id_len = strlen($order_id_main);
    $order_id_sum = 0;
    for ($i = 0; $i < $order_id_len; $i++) {
        $order_id_sum += (int)(substr($order_id_main, $i, 1));
    }
    $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);

    return $order_id;
}

/**
 * 获取img标签内容
 * @param $str
 * @param string $ext
 * @return array
 */
function imgMatch($str, $ext = 'jpg|jpeg|gif|bmp|png')
{
    $list = array();  //这里存放结果map
    $c1 = preg_match_all('/<img\s.*?>/', $str, $m1);  //先取出所有img标签文本

    for ($i = 0; $i < $c1; $i++) {     //对所有的img标签进行取属性
        $c2 = preg_match_all('/(\w+)\s*=\s*(?:(?:(["\'])(.*?)(?=\2))|([^\/\s]*))/', $m1[0][$i], $m2);  //匹配出所有的属性
        for ($j = 0; $j < $c2; $j++) {  //将匹配完的结果进行结构重组
            $list[$i][$m2[1][$j]] = !empty($m2[4][$j]) ? $m2[4][$j] : $m2[3][$j];
        }
    }
    //过滤不需要的扩展名图片
    $newList = array();
    $extArr = explode('|', $ext);
    foreach ($list as &$item) {
        if (in_array(substr(strrchr($item['src'], '.'), 1), $extArr)) {
            $newList[] = $item;
        }
    }
    unset($list);
    return $newList;
}

/**
 * Description:按行读取文件内容进行过滤匹配
 * server_name     : 虚拟主机的主机名称
 * remote_addr   : 远程客户端的ip地址
 * remote_user     : 远程客户端用户名称
 * time_local    : 访问的时间与时区
 * status      : 记录请求返回的http状态码
 * body_bytes_sent   : 发送给客户端的文件主体内容的大小
 * http_referer          : 从哪个页面链接访问过来
 * http_user_agent   : 客户端浏览器信息
 * http_x_forwarded_for    : 客户端的真实ip
 *
 * @param $filename
 * @return array
 */
function readLogContent($filename)
{
    $p = '/^(\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3})\s-\s(.*)\s\[(.*)\]\s"(.*)\"\s(\d{3})\s(\d+)\s"(.*)"\s\"(.*)\"(.*)$/u';

    $px_content = array();
    $fh = @fopen($filename, 'r');

    if ($fh) {
        while (!feof($fh)) {
            $s_line = fgets($fh, 4096);

            preg_match($p, $s_line, $a_match);

            if (isset($a_match[4]) && preg_match('/hm.js/', $a_match[4])) {
                $px_content[] = $a_match;
            }
        }
    }
    @fclose($fh);

    return $px_content;
}

/**
 * 将字符串参数变为数组
 * @param $query
 * @return array array (size=10)
 * 'm' => string 'content' (length=7)
 * 'c' => string 'index' (length=5)
 * 'a' => string 'lists' (length=5)
 * 'catid' => string '6' (length=1)
 * 'area' => string '0' (length=1)
 * 'author' => string '0' (length=1)
 * 'h' => string '0' (length=1)
 * 'region' => string '0' (length=1)
 * 's' => string '1' (length=1)
 * 'page' => string '1' (length=1)
 */
function convertUrlQuery($query)
{
    $queryParts = explode('&', $query);
    $params = array();
    if ($queryParts) {
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            if (isset($item[1])) {
                $params[$item[0]] = $item[1];
            }
        }
    }

    return $params;
}

/**
 * 将参数变为字符串
 * @param $array_query
 * @return string string 'm=content&c=index&a=lists&catid=6&area=0&author=0&h=0®ion=0&s=1&page=1' (length=73)
 */
function getUrlQuery($array_query)
{
    $tmp = array();
    foreach ($array_query as $k => $param) {
        $tmp[] = $k . '=' . $param;
    }
    $params = implode('&', $tmp);
    return $params;
}

/**
 * 获取毫秒数
 * @return float
 */
function getMillisecond()
{
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
}

/**
 * 对数据进行urlencode
 * @param $array
 * @return array|string
 */
function dataConvert($array)
{
    if (empty($array)) {
        return false;
    }

    if (is_array($array)) {
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $array[urlencode($key)] = dataConvert($val);
            } else {
                $array[urlencode($key)] = urlencode($val);
            }
            if (urlencode($key) != $key) {
                unset($array[$key]);
            }
        }
        return $array;
    } else {
        return urlencode($array);
    }
}

/**
 * 对数据进行json编码
 * @param $data
 * @return string
 */
function jsonMaker($data)
{
    if (empty($data)) {
        return false;
    }
    return urldecode(json_encode(dataConvert($data)));
}

/**
 * 对象数组转为普通数组
 *
 * AJAX提交到后台的JSON字串经decode解码后为一个对象数组，
 * 为此必须转为普通数组后才能进行后续处理，
 * 此函数支持多维数组处理。
 *
 * @param array
 * @return array
 */
function objarray_to_array($obj)
{
    $ret = array();
    foreach ($obj as $key => $value) {
        if (gettype($value) == "array" || gettype($value) == "object") {
            $ret[$key] = objarray_to_array($value);
        } else {
            $ret[$key] = $value;
        }
    }
    return $ret;
}

/**
 * 推送消息到客户端
 * @param int $uid
 * @param string $message
 * @return bool|mixed
 */
function pushMessage($uid = 0, $message = '')
{
    if ($uid == 0) {
        return false;
    }

    if (empty($message)) {
        return false;
    }

    // 推送的url地址，上线时改成自己的服务器地址
    $push_api_url = config('app.push_api_url');
    $post_data = array(
        "type" => "publish",
        "content" => $message,
        "to" => $uid,
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $push_api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

/**
 * 生成C端url地址
 * @param $id_4s
 * @param $path eq "#/field/writeoff?win_id=%s"
 * @param array $param eq "[1,59,'测试']"
 * @return string
 */
function makeClientUrl($id_4s, $path, $param = [])
{
    $url = 'http://' . $id_4s . '.' . config('app.shop_url') . '/' . sprintf($path, ...$param);
    return $url;
}

/**
 * 生成B端url地址
 * @param $path eq "#/field/writeoff?win_id=%s"
 * @param array $param eq "[1,59,'测试']"
 * @return string
 */
function makeWorkUrl($path, $param = [])
{
    $url = 'http://' . config('app.work_url') . sprintf($path, ...$param);
    return $url;
}

/**
 * 获取当前URL
 *
 * @return string
 */
function current_url()
{
    $protocol = (!empty($_SERVER['HTTPS'])
        && $_SERVER['HTTPS'] !== 'off'
        || $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';

    if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    } else {
        $host = $_SERVER['HTTP_HOST'];
    }

    return $protocol . $host . $_SERVER['REQUEST_URI'];
}

/**
 *  结果返回
 * @param $err_code int 1=失败 0=成功
 * @param $data array 结果
 * @return json
 */
function output($err_code, $data)
{
    $out_data = array(
        'errcode' => $err_code,
        'data' => $data
    );
    return response()->json($out_data);
}

/**
 *  概率算法
 * @param a奖概率20%，b奖概率30%，c奖概率50%
 * @param $proArr = ['a'=>20,'b'=>30,'c'=>50]
 * @return a || b || c
 */
function get_rand($proArr) {
    $result = '';
    //概率数组的总概率精度
    $proSum = array_sum($proArr);
    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);             //抽取随机数
        if ($randNum <= $proCur) {
            $result = $key;                         //得出结果
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);
    return $result;
}