<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;

class IndexController extends Controller
{
    public function index()
    {
        dd(1111);
        // 生成token
        $token = '';
        $user = User::first();
        //dd($user);
        $token = JWTAuth::fromUser($user);
        return $token;
    }
}
