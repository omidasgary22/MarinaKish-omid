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
        return response()->json(['message' => 'قانون با موفقیت ایجاد شد', 'rule' => $rule], 201);
    }

    public function show($id)
    {
        $rule = Rule::findOrFill($id);
        return response()->json(['rule' => $rule]);
    }

    public function update(Request $request, $id)
    {
        $rule = Rule::findOrFail($id);
        $rule->update($request->all());
        return response()->json(['message' => 'قانون با موفیقیت به روز رسانی شد', 'rule' => $rule]);
    }
}
