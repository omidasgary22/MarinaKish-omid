<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFAQRequest;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index()
    {
        $faqs = FAQ::all();
        return response()->json(['faqs' => $faqs]);
    }

    public  function store(StoreFAQRequest $request)
    {
        $faq = FAQ::create($request->toArray());
        return response()->json(['message' => 'سوال با موفقیت ایجاد شد', 'faq' => $faq], 201);
    }
}
