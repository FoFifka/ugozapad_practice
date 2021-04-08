<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    public function getGroups() {
        $groups = Group::all();

        return $groups;
    }

    public function addGroup(Request $request) {
        $group = new Group();

        $group['group_name'] = $request['group_name'];
        $group->save();

        return $group;
    }
}
