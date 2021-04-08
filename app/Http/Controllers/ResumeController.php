<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function getResumes(Request $request) {
        return Resume::all()->where('user_id', '=', $request['id']);
    }
    public function addResume(Request $request) {
        $resume = new Resume();
        $resume['user_id'] = $request['user_id'];
        $resume['about_me'] = $request['about_me'];
        $resume->save();
        return $resume;
    }
    public function changeResume(Request $request) {
        $resume = Resume::find($request['id']);
        $resume['about_me'] = $request['about_me'];
        $resume->save();
        return $resume;
    }
    public function deleteResume(Request $request) {
        return Resume::destroy($request['id']);
    }
}
