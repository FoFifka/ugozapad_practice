<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Group;
use App\Models\Mark;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use function Sodium\add;

class UserController extends Controller
{
    public function getSignedUser(Request $request)
    {
        $user = $request->user();
        if ($user->expires_at < today()) {  // проверка устарел ли токен
            $user->api_token = null; // обнуление токена
            $user->save();
            return response()->json([
                'message' => 'Token is deprecated'
            ], 401); // токен устарел
        } else {
            $group = '';
            $permission = '';
            $company = '';
            $mark = 'Оценки пока нет';
            try {
                $group = Group::find($user->group_id)->group_name;
            } catch (\Exception $e) {
            }
            try {
                $permission = Permission::find($user->permission_id)->permission;
            } catch (\Exception $e) {
            }
            try {
                $company = Company::find($user->companies_id)->company_name;
            } catch (\Exception $e) {
            }
            try {
                $mark = Mark::find($user->mark_id)->mark;
            } catch (\Exception $e) {
            }
            return response()->json( // возвращение данных пользователя
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'patronymic' => $user->patronymic,
                    'email' => $user->email,
                    'group' => $group,
                    'permission_id' => $user->permission_id,
                    'permission' => $permission,
                    'companies_id' => $user->companies_id,
                    'company' => $company,
                    'mark_id' => $user->mark_id,
                    'mark' => $mark,
                    'about_me' => $user->about_me,
                    'avatar' => $user->avatar,
                    'api_token' => $user->api_token
                ]
                , 202);
        }
    }

    public function getUser(Request $request)
    {
        $user = User::find($request['id']);

        $group = '';
        $permission = '';
        $company = '';
        $mark = 'Оценки пока нет';
        try {
            $group = Group::find($user->group_id)->group_name;
        } catch (\Exception $e) {
        }

        try {
            $permission = Permission::find($user->permission_id)->permission;
        } catch (\Exception $e) {
        }

        try {
            $company = Company::find($user->companies_id)->company_name;
        } catch (\Exception $e) {
        }
        try {
            $mark =  $mark = Mark::find($user->mark_id)->mark;;
        } catch (\Exception $e) {
        }
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'surname' => $user->surname,
            'patronymic' => $user->patronymic,
            'email' => $user->email,
            'group' => $group,
            'permission_id' => $user->permission_id,
            'permission' => $permission,
            'companies_id' => $user->companies_id,
            'company' => $company,
            'mark_id' => $user->mark_id,
            'mark' => $mark,
            'about_me' => $user->about_me,
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
        $imageURL = 'images/' . $fileName;

        $user->avatar = $imageURL;
        $user->save();

        return response()->json(['url' => $imageURL]);
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getUsers()
    {
        $users = User::all();
        $users1 = array();
        foreach ($users as $user) {
            $group_name = null;
            if ($user['group_id'] != null) {
                $group = Group::find($user['group_id']);
                $group_name = $group['group_name'];
            }

            array_push($users1,
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
        return $users1;
    }

    public function getStudents()
    {
        $students = User::get()->where('permission_id', '=', 1);
        $students1 = array();
        foreach ($students as $student) {
            $group_name = null;
            if ($student['group_id'] != null) {
                $group = Group::find($student['group_id']);
                $group_name = $group['group_name'];
            }
            array_push($students1, [
                'id' => $student->id,
                'name' => $student->name,
                'surname' => $student->surname,
                'patronymic' => $student->patronymic,
                'email' => $student->email,
                'group' => $group_name,
                'avatar' => $student->avatar,
            ]);
        }
        return $students1;
    }

    public function deleteUser(Request $request)
    {
        User::destroy($request['id']);
        return "1";
    }

    public function createUser(Request $request)
    {
        $userdata = request()->validate([
            'name' => 'required',
            'surname' => 'required',
            'patronymic' => 'sometimes',
            'email' => 'required',
            'group_id' => 'sometimes',
            'permission_id' => 'sometimes',
            'companies_id' => 'sometimes',
        ], ['required' => 'Не может быть пустым'], ['required|unique:users' => 'Пользователь с таким логином уже существует']);

        $user = new User($userdata);
        $user['email'] = $userdata['email'];
        $user['permission_id'] = $request['permission_id'];
        $password = $this->generateRandomString(rand(10,15));
        $user['password'] = Hash::make($password);
        $user->save();
        $user['avatar'] = User::find($user['id'])->avatar;

        $_SESSION['newuser_password'] = $password;
        $_SESSION['newuser_email'] = $request['email'];
        Mail::send(['text' => 'mail.newusermail'], ['name', 'fifka'], function ($message){
            $message->to($_SESSION['newuser_email'], 'LALALALALA')->subject('Title');
            $message->from('fireorbdhr@gmail.com', 'Юго-запад Практика');
        });

        return response()->json($user, 200);
    }

    public function changeUserMark(Request $request)
    {
        $user = User::find($request['id']);
        $user['mark_id'] = $request['mark_id'];
        $user->save();
        return 1;
    }
    public function changeUserAboutMe(Request $request) {
        $user = User::find($request['id']);
        $user['about_me'] = $request['about_me'];
        $user->save();

        return $user['about_me'];
    }
}



