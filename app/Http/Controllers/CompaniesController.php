<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use App\Models\Resume;
use App\Models\SentResumes;
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

        if($request->file('image') != null) {
            $fileName = $this->generateRandomString(rand(10, 30)) . ".jpg";
            $request->file('image')->move(public_path("images/"), $fileName);
            $imageURL = 'images/'.$fileName;

            $company['company_image'] = $imageURL;
        }

        $company['id'] = $id;
        $company['company_name'] = $request['company_name'];
        $company['company_description'] = $request['company_description'];
        $company->save();
        $company = Company::find($company['id']);

        return $company;
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

    public function updateCompanyImage(Request $request)
    {
        $company = Company::find($request['id']);

        $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        $fileName = $this->generateRandomString(rand(10, 30)) . ".jpg";
        $request->file('image')->move(public_path("images/"), $fileName);
        $imageURL = 'images/'.$fileName;

        $company['company_image'] = $imageURL;
        $company->save();

        return response()->json(['url' => $imageURL]);
    }

    public function resumesSent(Request $request) {
        $sentResumes = SentResumes::all()->where('company_id', '=', $request['company_id']);
        $response = [];
        foreach ($sentResumes as $sentResume) {
            $resume = Resume::find($sentResume['resume_id']);
            $user = User::find($resume['user_id']);
            $user_group = null;
            try {
                $user_group = Group::find($user['group_id']);
            } catch (\Exception $e) {

            }
            $response[] = [
                'id' => $sentResume['id'],
                'resume_id' => $sentResume['resume_id'],
                'company_id' => $sentResume['company_id'],
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_surname' => $user['surname'],
                'user_patronymic' => $user['patronymic'],
                'user_group' => $user_group['group_name']
            ];
        }
        return $response;
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
