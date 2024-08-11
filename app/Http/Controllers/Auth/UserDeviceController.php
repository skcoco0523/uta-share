<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserDevice;

class UserDeviceController extends Controller
{
    public function device_update(Request $request)
    {
        make_error_log("device_update.log","------------start-----------");
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_id' => 'required|string|max:255|unique:user_devices',
            'os' => 'required|string|max:255',
            'browser' => 'required|string|max:255',
            'endpoint' => 'required|string',
            'public_key' => 'required|string',
            'auth_token' => 'required|string',
        ]);
        make_error_log("device_update.log","validated=".print_r($validated,1));

        $device = UserDevice::updateOrCreateDevice($validated);

        if($device) return true;
        else        return false;
    }




}