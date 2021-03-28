<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function Sodium\add;

class UserController extends Controller
{
    public function getSignedUser(Request $request)
    {
        $user = $request->user();
        if($user->expires_at < today()) {  // проверка устарел ли токен
            $user->api_token = null; // обнуление токена
            $user->save();
            return response()->json([
                'message' => 'Token is deprecated'
            ], 401); // токен устарел
        } else {
            $group = '';
            try {
                $group = Group::find($user->group_id)->group_name;
            } catch (\Exception $e) {}
            return response()->json( // возвращение данных пользователя
                [
                       'id' => $user->id,
                       'login' => $user->login,
                       'name' => $user->name,
                       'surname' => $user->surname,
                       'patronymic' => $user->patronymic,
                       'group' => $group,
                       'permission' => $user->permission_id,
                       'companies_id' => $user->companies_id,
                       'avatar' => $user->avatar,
                       'api_token' => $user->api_token
                ]
                , 202);
        }
    }

    public function getUser(Request $request) {
        $user = User::find($request['id']);

        $group = '';
        $permission = '';
        try {
            $group = Group::find($user->group_id)->group_name;
        } catch (\Exception $e) {}

        try {
            $permission = Permission::find($user->permission_id)->permission;
        } catch (\Exception $e) {}

        return response()->json([
            'id' => $user->id,
            'login' => $user->login,
            'name' => $user->name,
            'surname' => $user->surname,
            'patronymic' => $user->patronymic,
            'group' => $group,
            'permission' => $permission,
            'companies_id' => $user->companies_id,
            'avatar' => $user->avatar,
            'api_token' => $user->api_token
        ]);
    }

    public function updateUserImage(Request $request)
    {
        $user = User::find($request['id']);

        $request->validate([
           'image' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        $fileName = $this->generateRandomString(rand(10, 30)) . ".jpg";
        $request->file('image')->move(public_path("images/"), $fileName);
        $imageURL = 'images/'.$fileName;

        $user->avatar = $imageURL;
        $user->save();

        return response()->json(['url' => $imageURL]);
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

    public function getUsers() {
        $users = User::all();
        $students = array();
        foreach ($users as $user) {
            $group_name = null;
            if($user['group_id'] != null) {
                $group = Group::find($user['group_id']);
                $group_name = $group['group_name'];
            }

            array_push($students,
            [
                'id' => $user['id'],
                'name' => $user['name'],
                'surname' => $user['surname'],
                'patronymic' => $user['patronymic'],
                'group' => $group_name,
                'permission' => $user['permission'],
                'companies_id' => $user['companies_id'],
                'avatar' => $user['avatar'],
            ]
            );
        }
        return $students;
    }

    public function getStudents() {
        $students = User::get()->where('permission_id', '=', 1);
        return $students;
    }

    public function deleteUser(Request $request) {
        User::destroy($request['id']);
        return "1";
    }

    public function createUser(Request $request) {
        $userdata = request()->validate([
            'login' => 'required|unique:users',
            'name' => 'required',
            'surname' => 'required',
            'patronymic' => 'sometimes',
            'password' => 'required',
            'email' => 'required',
            'group_id' => 'sometimes',
            'permission_id' => 'sometimes',
            'companies_id' => 'sometimes',
        ], ['required' => 'Не может быть пустым'], ['required|unique:users' => 'Пользователь с таким логином уже существует']);

        $userdata['password'] = Hash::make($userdata['password']);
        $user = new User($userdata);
        $user->save();

        return response()->json([$user],200);
    }
}



