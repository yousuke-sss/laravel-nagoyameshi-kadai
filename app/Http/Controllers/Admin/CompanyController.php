<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    public function index(){ 

    $company = Company::first();

    return view('admin.company.index', compact('company'));
    }
    
    public function edit(Company $company){
    
    return view('admin.company.edit', compact('company'));
    }

    public function update(Request $request, Company $company){

        // バリデーションを設定する
        $request->validate([
            'name' => 'required',                   // 入力必須
            'postal_code' => 'required|digits:7',   // 入力必須、数値、桁数7
            'address' => 'required',                // 入力必須
            'representative' => 'required',         // 入力必須
            'establishment_date' => 'required',     // 入力必須
            'capital' => 'required',                // 入力必須
            'business' => 'required',               // 入力必須
            'number_of_employees' => 'required',    // 入力必須
        ]);

        $company->name = $request->input('name');
        $company->postal_code = $request->input('postal_code');
        $company->address = $request->input('address');
        $company->representative = $request->input('representative');
        $company->establishment_date = $request->input('establishment_date');
        $company->capital = $request->input('capital');
        $company->business = $request->input('business');
        $company->number_of_employees = $request->input('number_of_employees');
        $company->save();

        return redirect()->route('admin.company.index')->with('flash_message', '会社概要を編集しました。');
    }

}
