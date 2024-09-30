<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() {}

    public function update(Request $request, $key)
    {
        $setting = Setting::where('key', $key)->update($request);
    }
}
