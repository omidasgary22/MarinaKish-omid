<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFAQRequest;
use App\Http\Requests\UpdateFAQRequest;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->can('faq.index')) {
            $faqs = FAQ::all();
            return response()->json(['faqs' => $faqs]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    public  function store(StoreFAQRequest $request)
    {
        if ($request->user()->can('faq.store')) {
            $faq = FAQ::create($request->toArray());
            return response()->json(['message' => 'سوال با موفقیت ایجاد شد', 'faq' => $faq], 201);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    public function update(UpdateFAQRequest $request, $id)
    {
        if ($request->user()->can('faq.update')) {
            $faq = FAQ::findorFail($id);
            $faq->update($request->toArray());
            return response()->json(['message' => 'سوال با موفقیت به روز رسانی شد.'], 200);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('faq.delete')) {
            $faq = FAQ::findOrFail($id);
            $faq->delete();
            return response()->json(['message' => 'سوال با موفقیت حذف شد.'], 200);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کا را ندارید'], 403);
        }
    }

    public function restore(Request $request, $id)
    {
        if ($request->user()->can('faq.restore')) {
            $faq = FAQ::onlyTrashed()->findOrFail($id);
            $faq->restore();
            return response()->json(['message' => 'سوال با موفقیت بازیابی شد.'], 200);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کا را ندارید'], 403);
        }
    }
}
