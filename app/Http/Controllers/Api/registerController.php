<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class registerController extends Controller
{
    public function register(Request $request){

//        $requestData = $request->validate([
//            'name' => 'min:44',
//            'email' => ['required', 'email'],
//            'password' => ['required', 'password'],

        $validation = $request->validate([
            'fullname' => ['required'],
            'email' => ['required', 'email','unique:users'],
             'password' => ['required'],
        ]);



       if(!$validation){
           throw new ValidationException('invalid data');
       }else{
           $validation['password'] = Hash::make($validation['password']);
       }


        $users = User::create([
            'name'=> $validation['fullname'],
            'email' => $validation['email'],
            'password' => $validation['password']
        ]);
        $accessToken = $users->createToken('accesss Token')->accessToken;


        return response()->json([
            'user'=>$users,
            'accessToken' => $accessToken
        ], 200);
    }
}
