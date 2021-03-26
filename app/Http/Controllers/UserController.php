<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request)
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
            } catch (\Exception $e) {

            }
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

    public function updateUserImage(Request $request)
    {
        $user = $request->user();

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
        return User::all();
    }
}
