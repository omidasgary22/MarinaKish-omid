<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::all();
        return response()->json(["setting" => $setting]);
    }

    public function update(Request $request)
    {
        $setting = Setting::where('key', $request->key)->first();

        if ($setting) {
            $setting->update($request->all);
        } else {
            $setting = Setting::create($request->all());
        }

        return response()->json($setting);
    }
}
