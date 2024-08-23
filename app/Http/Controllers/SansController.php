<?php

namespace App\Http\Controllers;

use App\Models\Sans;
use Illuminate\Http\Request;

class SansController extends Controller
{
    /**
     * Store new sessions for a product.
     *
     * @param int $time Duration of each session in minutes.
     * @param int $pending Time pending between sessions in minutes.
     * @param int $total Total capacity for each session.
     * @param \Carbon\Carbon $start_time Start time of the first session.
     * @param \Carbon\Carbon $ended_at End time for creating sessions.
     * @param int $id Product ID.
     * @param int $age_limit Age limit for the session.
     */
    public static function store($time, $pending, $total, $start_time, $ended_at, $id, $age_limit = 0)
    {
        while ($start_time->lessThan($ended_at)) {
            Sans::create([
                'product_id' => $id,
                'start_time' => $start_time->toTimeString(),
                'capacity' => $total,
                'age_limit' => $age_limit,
            ]);
            $start_time->addMinutes($pending + $time);
        }
    }

    /**
     * Update sessions for a product by removing old ones and creating new ones.
     *
     * @param int $time Duration of each session in minutes.
     * @param int $pending Time pending between sessions in minutes.
     * @param int $total Total capacity for each session.
     * @param \Carbon\Carbon $start_time Start time of the first session.
     * @param int $id Product ID.
     * @param \Carbon\Carbon $ended_at End time for creating sessions.
     */
    public static function update($time, $pending, $total, $start_time, $id, $ended_at)
    {
        $sans = Sans::where('product_id', $id)->get();
        foreach ($sans as $san) {
            $san->delete();
        }
        self::store($time, $pending, $total, $start_time, $ended_at, $id);
    }
}
