<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    public function getMark(Request $request) {
        $mark = Mark::find($request['id']);
        return $mark;
    }
    public function getMarks() {
        return Mark::all();
    }

}
