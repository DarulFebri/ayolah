<?php

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

if (! function_exists('logActivity')) {
    function logActivity($activity, $module = null)
    {
        $log = [];
        $log['user_id'] = Auth::check() ? Auth::id() : null;
        $log['activity'] = $activity;
        $log['module'] = $module;
        $log['ip_address'] = Request::ip();
        $log['user_agent'] = Request::header('user-agent');

        Activity::create($log);
    }
}
