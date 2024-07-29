<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index()
    {
        $faqs = FAQ::all();
        return response()->json(['faqs' => $faqs]);
    }
}
