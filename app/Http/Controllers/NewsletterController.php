<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterRequest;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{

    public function index(Request $request)
    {
        if ($request->user()->can('news.index')) {
            $newsletter = Newsletter::all();
            return response()->json($newsletter);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز زا ندارید']);
        }
    }


    public function store(StoreNewsletterRequest $request)
    {
        if ($request->user()->can('news.store')) {
            $newsletter = Newsletter::create($request->toArray());
            return response()->json(['success', 'ایمیل شما با موفقیت ثبت شد.']);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }
}
