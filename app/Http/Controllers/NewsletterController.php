<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store( $request)
    {
        $newsletter = Newsletter::create($request->toArray());
        return redirect()->back()->with('success', 'ایمیل شما با موفقیت ثبت شد.');
    }
}
