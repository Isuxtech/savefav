<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;

class loginController extends Controller
{
    public function login(Request $request){
        $loginData = $request->validate([
           'email' => ['bail','required', 'email'],
           'password' => ['required']
        ]);

        if(!$loginData){
            throw ValidationException();
        }
        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            $users = User::where('email', $loginData['email'])->first();
            $accessToken = $users->createToken('Accesss token')->accessToken;
            return response(['accessToken' =>$accessToken]);
        }

        return response()->json(['error'=>'Incorrect username/password'],400);

    }

    public function logout(Request $request){
        return auth()->user()->token()->revoke();
//        return 'success';
//        dd($request->user() );
    }
}
