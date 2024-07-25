<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRuleReuest;
use App\Http\Requests\UpdateRuleRequest;
use App\Models\Rule;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    public function index()
    {
        $rules = Rule::all();
        return response()->json(['rules' => 'rules']);
    }

    public function store(StoreRuleReuest $request)
    {
        $rule = Rule::create($request->all());
        return response()->json(['message' => 'قانون با موفقیت ایجاد شد', 'rule' => $rule], 201);
    }

    public function show($id)
    {
        $rule = Rule::findOrFill($id);
        return response()->json(['rule' => $rule]);
    }

    public function update(UpdateRuleRequest $request, $id)
    {
        $rule = Rule::findOrFail($id);
        $rule->update($request->all());
        return response()->json(['message' => 'قانون با موفیقیت به روز رسانی شد', 'rule' => $rule]);
    }

    public function destroy($id)
    {
        $rule = Rule::findOrFail($id);
        $rule->delete($id);
        return response()->json(['message' => 'قانون با موفقیت حذف شد']);
    }

    public function restore($id)
    {
        $user = Rule::onlyTrashed()->findOrFail($id);
        $user->restore();
        return response()->json(['message' => 'قانون مورد نظر  با موفقیت بازیابی شد.'], 200);
    }
}
