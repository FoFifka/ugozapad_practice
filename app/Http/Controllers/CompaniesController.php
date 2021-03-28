<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompaniesController extends Controller
{
    public function getCompanies() {
        return Company::all();
    }
    public function getCompany(Request $request) {
        $id = $request['id'];

        return Company::find($id);
    }
    public function addCompany(Request $request) {
        $company = new Company();
        $id = 1;
        try {
            $id = Company::get()->last()->id + 1;
        } catch (\Exception $exception) {
            $id = 1;
        }
        $company['id'] = $id;
        $company['company_name'] = $request['company_name'];
        $company['company_description'] = $request['company_description'];
        $company->save();
    }

    public function deleteCompany(Request $request) {
        $vacansies = Vacancy::get()->where('companies_id', '=', $request['companies_id']);
        $users = User::get()->where('companies_id', '=', $request['companies_id']);
        foreach ($vacansies as $vacansy) {
            $vacansy->destroy($vacansy['id']);
        }
        foreach ($users as $user) {
            $user->destroy($user['id']);
        }
        Company::destroy($request['companies_id']);
        return '1';
    }
}
