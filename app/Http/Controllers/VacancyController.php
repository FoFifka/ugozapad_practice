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
        $id = 1;
        try {
            $id = Vacancy::get()->last()->id + 1;
        } catch (\Exception $exception) {
            $id = 1;
        }
        $vacancy['id'] = $id;
        $vacancy['vacancy_name'] = $request['vacancy_name'];
        $vacancy['vacancy_description'] = $request['vacancy_description'];
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
