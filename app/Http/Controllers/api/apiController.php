<?php

namespace App\Http\Controllers\api;
use App\Models\User;
use App\Http\Controllers\Controller;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class apiController extends Controller
{
    function register(Request $request){

        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'password'=>'required'
        ]);

        $user = User::create([
            'name' => $request->input('name'), 
            'email' => $request->input('email'), 
            'password' => bcrypt($request->input('password')), 
        ]);

       $Token= $user->createToken('token-name', ['server:update'])->plainTextToken;

       return response()->json([

         'status'=>true,
         'Token'=>$Token
       ]);

    }
}
