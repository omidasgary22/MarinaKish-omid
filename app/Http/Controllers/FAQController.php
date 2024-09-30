<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFAQRequest;
use App\Http\Requests\UpdateFAQRequest;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the FAQs.
     * This method retrieves and returns all FAQ records.
     */
    public function index(Request $request)
    {
        if ($request->user()->can('faq.index')) {
            $faqs = FAQ::paginate(10);
            return response()->json(['faqs' => $faqs]);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    /**
     * Store a newly created FAQ in storage.
     * This method creates a new FAQ record if the user has the necessary permission.
     */
    public function store(StoreFAQRequest $request)
    {
        if ($request->user()->can('faq.store')) {
            $faq = FAQ::create($request->toArray());
            return response()->json(['message' => 'سوال با موفقیت ایجاد شد', 'faq' => $faq], 201);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    /**
     * Update the specified FAQ in storage.
     * This method updates the specified FAQ record if the user has the necessary permission.
     */
    public function update(UpdateFAQRequest $request, $id)
    {
        if ($request->user()->can('faq.update')) {
            $faq = FAQ::findOrFail($id);
            $faq->update($request->toArray());
            return response()->json(['message' => 'سوال با موفقیت به روز رسانی شد.'], 200);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    /**
     * Remove the specified FAQ from storage.
     * This method deletes the specified FAQ record if the user has the necessary permission.
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->can('faq.delete')) {
            $faq = FAQ::findOrFail($id);
            $faq->delete();
            return response()->json(['message' => 'سوال با موفقیت حذف شد.'], 200);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }

    /**
     * Restore the specified FAQ from trash.
     * This method restores the specified FAQ record if the user has the necessary permission.
     */
    public function restore(Request $request, $id)
    {
        if ($request->user()->can('faq.restore')) {
            $faq = FAQ::onlyTrashed()->findOrFail($id);
            $faq->restore();
            return response()->json(['message' => 'سوال با موفقیت بازیابی شد.'], 200);
        } else {
            return response()->json(['message' => 'شما دسترسی انجام این کار را ندارید'], 403);
        }
    }
}
