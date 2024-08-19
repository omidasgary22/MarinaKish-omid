<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterRequest;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(StoreNewsletterRequest $request)
    {
        $newsletter = Newsletter::create($request->toArray());
        return response()->json(['success', 'ایمیل شما با موفقیت ثبت شد.']);
    }
}
