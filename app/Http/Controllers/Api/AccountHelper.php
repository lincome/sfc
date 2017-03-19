<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

trait AccountHelper
{
    /**
     * 当前4s店ID.
     *
     * @return Account|null
     */
    public function s4account()
    {
        return request()->s4_id ? request()->s4_id : 50000;
    }

    /**
     * 当前用户ID.
     *
     * @return Account|null
     */
    public function current_uid()
    {
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return false;
        }
        Log::info($user);
        return $user->id;
    }

    /**
     * 当前用户.
     *
     * @return Customer|null
     */
    public function current_user()
    {
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return false;
        }

        return $user;
    }

    /**
     * 读取用户缓存，主要用户展示，重要逻辑不要使用
     */
    public function current_user_cache()
    {

    }

    /**
     * 当前用户ID.
     *
     * @return Account|null
     */
    public function current_customer()
    {
        return app('bee.current_customer');
    }
}
