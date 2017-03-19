<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\User;
use App\Order;
use App\OrderPassager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{

    private $page_size; //当前控制器,所有分页，每页显示记录数

    public function __construct()
    {
        $this->page_size = 10;
    }

    /**
     * 登录
     */
    public function login(Request $request)
    {
        $phone_number = $request->phone_number;
        $password = $request->password;
        $remember = $request->remember ? $request->remember : false;

        if (empty($phone_number) || empty($password)) {
            return $this->output(1, '账号或密码不能为空!');
        }
        $result = Auth::attempt(['phone_number' => $phone_number, 'password' => $password], $remember);
        Log::info($result);
        if ($result) {
            $user = User::where('phone_number', $phone_number)->first();
            $token = JWTAuth::fromUser($user);
            $data['token'] = $token;
            return $this->output(0, $data);
        } else {
            return $this->output(2, '账号或密码错误!');
        }
    }

    /**
     * 注册
     */
    public function regedit(Request $request)
    {
        $type = $request->type;
        if ($type == ['乘客']) {
            $type = 0;
        } elseif ($type == ['车主']) {
            $type = 1;
        } else {
            return $this->output(1, '类型有误!');
        }
        $name = $request->name;
        $email = $request->email;
        $phone_number = $request->phone_number;
        $password = $request->password;

        $regedit_email = User::where('email', $email)->first();
        if ($regedit_email) {
            return $this->output(2, '该邮箱已被注册!');
        }

        $regedit_phone_number = User::where('phone_number', $phone_number)->first();
        if ($regedit_phone_number) {
            return $this->output(3, '该手机号已被注册!');
        }

        $plate_number = $request->plate_number;
        if ($type == 1) {
            $regedit_plate_number = User::where('plate_number', $plate_number)->first();
            if ($regedit_plate_number) {
                return $this->output(5, '该车牌号已存在!');
            }

            if (empty($plate_number)) {
                return $this->output(4, '车主必须填写车牌号!');
            }
        }

        if (empty($phone_number) || empty($password)) {
            return $this->output(6, '账号或密码不能为空!');
        }
        $user = new User();
        $user->type = $type;
        $user->name = $name;
        $user->email = $email;
        $user->phone_number = $phone_number;
        $user->password = bcrypt($password);
        $user->plate_number = $plate_number;
        $result = $user->save();
        if (!$result) {
            return $this->output(7, '注册失败!');
        }
        //注册成功默认进入首页
        $user_data = User::find($user->id);
        $token = JWTAuth::fromUser($user_data);
        $data['token'] = $token;
        return $this->output(0, $data);
    }

    public function me(Request $request)
    {
        $user_id = $this->current_uid();
        $data = User::find($user_id);
        return $this->output(0, $data);
    }

    /**
     * 注销
     */
    public function logout()
    {
        session()->flush();

        return $this->output(0, '注销成功!');
    }

    /**
     * token生成
     */
    public function get_token(Request $request)
    {
        $user_id = $request->id;
        $user = User::find($user_id);
        $token = JWTAuth::fromUser($user);
        return $this->output(0, $token);
    }

    /**
     * 车主发布列表
     */
    public function czfb_lists(Request $request)
    {
        $data['city_start'] = $request->city_start ? $request->city_start : '';
        $data['city_end'] = $request->city_end ? $request->city_end : '';
        $page = (isset($request->page) && $request->page > 0) ? $request->page : 1;
        //dd($page);
        $limit = $page * $this->page_size;
        $list = Order::orderBy('id', 'desc')->where(function ($query) use ($data) {
            if (isset($data['city_start']) && $data['city_start'] != '') {
                $query->where('city_start', 'like', '%' . $data['city_start'] . '%');
            }
            if (isset($data['city_end']) && $data['city_end'] != '') {
                $query->where('city_end', 'like', '%' . $data['city_end'] . '%');
            }
            $query->where('user_id', $this->current_uid());
            $query->where('status', 1);
        })->take($limit)->get();
        if (count($list) < 0) {
            return $this->output(1, '没有数据了');
        }

        foreach ($list as $key => $item) {
            $list[$key]['city_start'] = json_decode($item->city_start);
            $list[$key]['city_end'] = json_decode($item->city_end);
        }
        return $this->output(0, $list);
    }

    /**
     * 乘客预定列表
     */
    public function ckyd_lists(Request $request)
    {
        $data['city_start'] = $request->city_start ? $request->city_start : '';
        $data['city_end'] = $request->city_end ? $request->city_end : '';
        $page = (isset($request->page) && $request->page > 0) ? $request->page : 1;
        //dd($page);
        $limit = $page * $this->page_size;
        $list = OrderPassager::orderBy('id', 'desc')->where(function ($query) use ($data) {
            if (isset($data['city_start']) && $data['city_start'] != '') {
                $query->where('city_start', 'like', '%' . $data['city_start'] . '%');
            }
            if (isset($data['city_end']) && $data['city_end'] != '') {
                $query->where('city_end', 'like', '%' . $data['city_end'] . '%');
            }
            $query->where('user_id', $this->current_uid());
//            $query->where('status', 1);
        })->take($limit)->get();
        if (count($list) < 0) {
            return $this->output(1, '没有数据了');
        }
        foreach ($list as $key => $item) {
            $order = Order::find($item->order_id);
            $list[$key]['start_date'] = $order->start_date;
            $list[$key]['city_start'] = json_decode($order->city_start);
            $list[$key]['city_end'] = json_decode($order->city_end);
        }
        return $this->output(0, $list);
    }

    /**
     * 发送邮件
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $email = $request->email;
        Log::info($email);
        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->output(1, '该邮箱不存在!');
        }
        $token = JWTAuth::fromUser($user);
        $url = config('app.url') . '#/account/PasswordReset?token=' . $token;
        Mail::send('email', ['user' => $user, 'url' => $url], function ($m) use ($user) {
            $m->to($user->email)->subject('重置密码!');
        });
        return $this->output(0, '发送成功!');
    }

    /**
     * 重置密码
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $user_id = $this->current_uid();
        $password = $request->password;
        $result = User::find($user_id)->update(['password' => bcrypt($password)]);
        if (!$result) {
            return $this->output(1, '修改失败!');
        }
        return $this->output(0, '修改成功!');
    }

    public function test(Request $request)
    {
       dd(Auth::guard()->check());

    }
}
