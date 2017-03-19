<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\OrderPassager;
use App\Order;
use Illuminate\Support\Facades\Log;

class OrderPassengerController extends Controller
{
    //
    public function __construct()
    {

    }

    /**
     * 乘客预定
     */
    public function reserve(Request $request)
    {
        $data['user_id'] = $this->current_uid();
        $order_id = $request->order_id;
        $data['mobile'] = 1;
        $seat_num = $request->seat_num;
        $data['remark'] = 1;

        $orderPassager = new OrderPassager;
        $orderPassager->user_id = $this->current_uid();
        $orderPassager->order_id = $order_id;
//        $orderPassager->mobile = $request->mobile;
        $orderPassager->seat_num = $seat_num;
        $orderPassager->remark = $request->remark;

        $result = $orderPassager->save();
        if (!$result) {
            return $this->output(1, '预定失败!');
        }
        //减座位数
        $order = Order::find($order_id);
        $seat = $order->seat - $seat_num;
        if ($seat < 0) {
            return $this->output(2, '座位数不足!');
        }
        $result = Order::where('id', $order_id)->update(['seat' => $seat]);
        if (!$result) {
            return $this->output(3, '预定失败!');
        }
        return $this->output(0, '预定成功!');
    }

    /**
     * 每个订单上的乘客
     */
    public function getUsersByOrderId(Request $request)
    {
        $order_id = $request->order_id;
        Log::info($order_id);
        $orderPassagers = OrderPassager::where('order_id', $order_id)->get();
        $data = [];
        foreach ($orderPassagers as $orderPassager) {
            $data[] = User::find($orderPassager->user_id);
        }
        return $this->output(0, $data);
    }
}
