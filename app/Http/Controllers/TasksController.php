<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function getTasks(Request $request)
    {
        //$tasks = DB::table('tasks')->get();
        $tasks = DB::table('tasks')->get();

        return $tasks;
    }

    public function create(Request $request)
    {
        $task = new Task;
        $task->header=$request->header;
        $task->description=$request->description;
        $task->group=$request->group;

        $task->save();

        if($task->save())
        {
            return ['success'=>'success'];
        }
        else
        {
            return ['success'=>'fail'];
        }
    }
}
