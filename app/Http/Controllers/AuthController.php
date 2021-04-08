<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signin(Request $request)
    {
        $email = $request['email'];
        $password = $request['password'];
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            if($user->api_token != null) {
                return response()->json([
                    'token' => $user->api_token
                ], 202);
            } else {
                $user->api_token = Auth::user()->createToken('accessToken')->accessToken;
                $user->expires_at = today()->addMonth();
                $user->save();
                return response()->json([
                    'token' => $user->api_token
                ], 202);
            }
            // Удалось зайти (сгенерить токен и вернуть)

        } else {
            return response()->json([
                'message' => 'Неверные данные!'
            ], 401);
            // Отказано
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->api_token = null;
        $user->save();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


    public function signup(Request $request)
    {
        $userdata = request()->validate([
            'login' => 'required|unique:users',
            'name' => 'required',
            'surname' => 'required',
            'patronymic' => 'sometimes',
            'password' => 'required',
            'group' => 'sometimes',
            'permission' => 'sometimes',
        ], ['required' => 'Не может быть пустым'], ['required|unique:users' => 'Пользователь с таким логином уже существует']);

        dd($userdata);

        $userdata['password'] = Hash::make($userdata['password']);
        $user = new User($userdata);
        $user->save();

        return response()->json([$user],200);
    }

}
