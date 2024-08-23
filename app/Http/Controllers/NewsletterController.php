<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterRequest;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the newsletters.
     * Returns all newsletters if the user has the appropriate permissions.
     */
    public function index(Request $request)
    {
        if ($request->user()->can('news.index')) {
            $newsletter = Newsletter::all();
            return response()->json($newsletter);
        } else {
            return response()->json(['message' => 'شما دسترسی مجاز را ندارید']);
        }
    }

    /**
     * Store a newly created newsletter in the database.
     * Accepts a validated request and creates a new newsletter entry if the user has the required permissions.
     */
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
