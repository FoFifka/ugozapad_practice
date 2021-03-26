<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VacancyController extends Controller
{
    public function addVacancy(Request $request)
    {
        $vacancy = new Vacancy;
        $vacancy['name'] = $request['name'];
        $vacancy['description'] = $request['description'];
        $vacancy['companies_id'] = $request['companies_id'];
        $vacancy->save();
    }

    public function getVacancies(Request $request)
    {
        return Vacancy::get()->where('companies_id', '=', $request['companies_id']);
    }

    public function getVacancy(Request $request) {
        return Vacancy::find($request['id']);
    }

    public function deleteVacancy(Request $request) {
        return Vacancy::destroy($request['id']);
    }
}
