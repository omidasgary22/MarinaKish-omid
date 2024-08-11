<?php

namespace App\Http\Controllers;

use App\Models\Sans;
use Illuminate\Http\Request;

class SansController extends Controller
{
    public static function store($time, $pending, $total, $start_time, $ended_at, $id)
    {
        while ($start_time->lessThan($ended_at)) {
            Sans::create([
                'product_id' => $id,
                'start_time' => $start_time->toTimeString(),
                'capacity' => $total,
            ]);
            $start_time->addMinutes($pending + $time);
        }
    }

    public static function update($time, $pending, $total, $start_time, $id, $ended_at)
    {
        $sans = Sans::where('product_id', $id)->get();
        foreach ($sans as $san) {
            $san->delete();
        }
        self::store($time, $pending, $total, $start_time, $ended_at, $id);
    }
}
