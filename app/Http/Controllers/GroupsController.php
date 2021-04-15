<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\WillingPracticeUser;
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

    public function changeGroup(Request $request) {
        $group = Group::find($request['group_id']);
        $group['group_name'] = $request['group_name'];
        $group->save();
        return $group;
    }

    public function deleteGroup(Request $request) {
        $users = User::get()->where('group_id', '=', $request['group_id']);
        foreach ($users as $user) {
            $willingPracticeUsers = WillingPracticeUser::get()->where('user_id', '=', $user['id']);
            foreach ($willingPracticeUsers as $willingPracticeUser) {
                WillingPracticeUser::destroy($willingPracticeUser['id']);
            }
            User::destroy($user['id']);
        }
        Group::destroy($request['group_id']);
        return response()->json([
            'message' => 'группа успешно удалена'
        ]);
    }
}
