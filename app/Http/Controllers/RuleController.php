<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index()
    {
        $rules = Rule::all();
        return response()->json(['rules' => 'rules']);
    }

    public function store(Request $request)
    {
      $rule = Rule::create($request->all());
      return response()->json(['message' => 'قانون با موفقیت ایجاد شد','rule' => $rule],201);
    }
}
