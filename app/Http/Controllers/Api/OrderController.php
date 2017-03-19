<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Order;

class OrderController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserValidator
     */
    protected $validator;

    private $page_size; //当前控制器,所有分页，每页显示记录数

    public function __construct()
    {
        $this->page_size = 10;
    }

    /**
     * 首页
     */
    public function index(Request $request)
    {
        $data['city_start'] = $request->city_start ? $request->city_start : '';
        $data['city_end'] = $request->city_end ? $request->city_end : '';
        $data['start_date'] = $request->start_date ? $request->start_date : '';
        Log::info($data['start_date']);
        if(!empty($data['city_start'])){
          $data['city_start']=json_encode(explode(",", $data['city_start']));
        }
        if(!empty($data['city_end'])){
          $data['city_end']=json_encode(explode(",", $data['city_end']));
        }

        $page = (isset($request->page) && $request->page > 0) ? $request->page : 1;
        //dd($page);
        $limit = $page * $this->page_size;
        DB::enableQueryLog();
        $list = Order::orderBy('id', 'desc')->where(function ($query) use ($data) {
            if (isset($data['city_start']) && $data['city_start'] != '') {
                $query->where('city_start', $data['city_start']);
            }
            if (isset($data['city_end']) && $data['city_end'] != '') {
                $query->where('city_end', $data['city_end']);
            }
            $query->where('start_date','like', $data['start_date'].'%');
            // $query->where('status', 1);
            $query->where('seat', '>', 0);
        })->take($limit)->get();
        Log::info(DB::getQueryLog());
        if (count($list) < 0) {
            return $this->output(1, '没有数据了');
        }
        foreach ($list as $key => $item) {
            $list[$key]['city_start'] = json_decode($item->city_start);
            $list[$key]['city_end'] = json_decode($item->city_end);
        }
        //Log::info($limit);
        return $this->output(0, $list);
    }


    /**
     *  发布订单
     */
    public function save(Request $request)
    {
        Log::info($request->all());
        /*$rule = [
                        ['city_start', 'require|max:25', '出发地点必须|名称最多不能超过25个字符'],
                        ['city_end', 'require|max:25', '目的地必须|名称最多不能超过25个字符'],
                        ['start_date', 'require', '出发时间必须'],
                        ['mobile', 'require|number', '手机号必须|手机号必须是数字'],
                        ['price', 'require', '价格必须'],
                        ['seat', 'require|number', '座位数必须|座位数必须为数字'],
                    ];
                    $validate = new Validate($rule);

                    if (!$validate->check($data)) {
                        return $validate->getError();
        */
        $user_id = $this->current_uid();

        $user = User::find($user_id);
        if (!$user || $user->type != 1) {
            return $this->output(1, '您还未注册成为车主，不能发布信息!');
        }
        if ($user->status == 0) {
            return $this->output(2, '您的账号还未审核通过，请等待管理员审核!');
        }
        //一天只能发布一次顺风车
        $today_date = date('Y-m-d');
        //dd($today_date);
        //DB::enableQueryLog();
        $today_order_num = Order::where('user_id', $user_id)->where('created_at', 'like', $today_date . '%')->count();
        //Log::info(DB::getQueryLog());
        //dd($today_order_num);
        // if ($today_order_num > 0) {
        //     return $this->output(3, '您今天已经发布过顺风车了，一天只能发送一次!');
        // }
        $flight = new Order;

        $flight->user_id = $user_id;
        $flight->city_start = json_encode($request->city_start);
        $flight->city_end = json_encode($request->city_end);
        $flight->start_date = $request->start_date;
        $flight->mobile = $request->mobile;
        $flight->price = $request->price;
        $flight->seat = $request->seat;
        $flight->remark = $request->remark;
        $result = $flight->save();

        if (!$result) {
            return $this->output(4, '发布失败!');
        }
        return $this->output(0, '发布成功!');
    }

    //加载更多
    public function loadmore()
    {
        $page = $_POST['page'];

        $start = ($page - 1) * 10;

        $list = DB::table('order')->limit($start, 10)->orderBy('id', 'desc')->get();
        if (!$list) {
            return $this->jsonOutput(['errorno' => 1]);
        }
        $str = '';
        if (count($list) == 0) {
            $data['more'] = 0;
        } else {
            foreach ($list as $key => $val) {
                $str .= '<dl>';
                $str .= '<dt>出发地：' . $val->city_start . '->目的地：' . $val->city_end . '</dt>';
                $str .= '<dd>发车时间：' . $val->start_date . '，座位：' . $val->seat . '，价格：¥' . $val->price . ',手机号：' . $val->mobile . '</dd>';
                $str .= '</dl>';
            }
            $data['more'] = 1;
        }

        $data['str'] = $str;
        $data['errorno'] = 0;

        return $data;
    }

    public function getInfoByid(Request $request)
    {
        $id = $request->id;
        $data = Order::find($id);
        $data['city_start'] = json_decode($data->city_start);
        $data['city_end'] = json_decode($data->city_end);

        return $this->output(0, $data);
    }


}
