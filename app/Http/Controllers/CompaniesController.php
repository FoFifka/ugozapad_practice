<?php

namespace App\Http\Controllers;

use App\Models\Company;
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
}
