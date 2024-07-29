<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFAQRequest;
use App\Http\Requests\UpdateFAQRequest;
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

    public function update(UpdateFAQRequest $request, $id)
    {
        $faq = FAQ::findorFail($id);
        $faq->update($request->toArray());
        return response()->json(['message' => 'سوال با موفقیت به روز رسانی شد.'], 200);
    }

    public function destroy($id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();
        return response()->json(['message' => 'سوال با موفقیت حذف شد.'], 200);
    }

    public function restore($id)
    {
        $faq = FAQ::onlyTrashed()->findOrFail($id);
        $faq->restore();
        return response()->json(['message' => 'سوال با موفقیت بازیابی شد.'], 200);
    }
}
