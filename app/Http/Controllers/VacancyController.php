<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Vacancy;
use App\Models\WillingPracticeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VacancyController extends Controller
{
    public function addVacancy(Request $request)
    {
        $vacancyData = $request->validate([
            'vacancy_name' => 'required',
            'vacancy_description' => 'required',
        ]);
        $company = Company::findOrFail($request->get('company_id'));
        return $company->vacancies()->create($vacancyData);
    }

    public function getVacancies(Request $request)
    {
        return Company::find($request->get('company_id'))->vacancies;
        // return Company::find($request->get('company_id'))->vacancies;
    }

    public function getVacancy(Request $request) {
        return Vacancy::find($request['id']);
    }

    public function deleteVacancy(Request $request) {
        $willingpracticeusers = WillingPracticeUser::get()->where('vacancy_id', '=', $request['id']);
        foreach ($willingpracticeusers as $willingpracticeuser) {
            WillingPracticeUser::destroy($willingpracticeuser['id']);
        }
        return Vacancy::destroy($request['id']);
    }
}
